<?php

namespace Tests\Feature;

use App\Models\Bom;
use App\Models\BomItem;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Machine;
use App\Models\MaintenanceTicket;
use App\Models\Material;
use App\Models\ProcessTemplate;
use App\Models\ProcessTemplateStage;
use App\Models\Product;
use App\Models\Shift;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WorkOrder;
use App\Services\Automation\IssueAutomationEngine;
use App\Services\Inventory\StockService;
use App\Services\Production\ProductionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoryOperationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_fifo_issues_the_oldest_lot_first(): void
    {
        $warehouse = Warehouse::query()->create(['code' => 'RAW', 'name' => 'Raw', 'kind' => 'raw']);
        $material = Material::query()->create([
            'sku' => 'RESIN',
            'name' => 'Resin',
            'category' => 'resin',
            'uom' => 'kg',
            'valuation_method' => 'fifo',
            'standard_cost' => 100,
            'min_qty' => 0,
            'reorder_qty' => 0,
            'is_active' => true,
        ]);

        $stock = app(StockService::class);
        $first = $stock->receive($material, $warehouse, 'A', 10, 100);
        $stock->receive($material, $warehouse, 'B', 10, 140);

        $cost = $stock->issue($material->fresh(), 10, $warehouse);

        $this->assertEquals(1000, $cost);
        $this->assertEquals(0, (float) $first->fresh()->qty_on_hand);
        $this->assertEquals(10, (float) $material->fresh()->onHand());
    }

    public function test_weighted_average_values_the_issue_at_the_blended_cost(): void
    {
        $warehouse = Warehouse::query()->create(['code' => 'RAW', 'name' => 'Raw', 'kind' => 'raw']);
        $material = Material::query()->create([
            'sku' => 'PIG',
            'name' => 'Pigment',
            'category' => 'pigment',
            'uom' => 'kg',
            'valuation_method' => 'weighted_average',
            'standard_cost' => 100,
            'min_qty' => 0,
            'reorder_qty' => 0,
            'is_active' => true,
        ]);

        $stock = app(StockService::class);
        $stock->receive($material, $warehouse, 'A', 10, 100);
        $stock->receive($material, $warehouse, 'B', 10, 200);

        $cost = $stock->issue($material->fresh(), 10, $warehouse);

        $this->assertEquals(1500.0, $cost);
    }

    public function test_shop_floor_consumes_the_mixing_bom_and_a_color_issue_holds_the_order(): void
    {
        $user = User::factory()->create(['role' => 'production_manager', 'is_active' => true]);
        $this->actingAs($user);

        $department = Department::query()->create(['code' => 'MIX', 'name' => 'Mixing']);
        $shift = Shift::query()->create(['name' => 'Day', 'starts_at' => '08:00', 'ends_at' => '17:00']);
        $operator = Employee::query()->create([
            'department_id' => $department->id,
            'shift_id' => $shift->id,
            'employee_code' => 'E-1',
            'name' => 'Mixer',
            'designation' => 'Operator',
            'hourly_rate' => 100,
            'hired_on' => now()->toDateString(),
        ]);
        $machine = Machine::query()->create([
            'code' => 'DRL-1',
            'name' => 'Drill',
            'machine_type' => 'drilling',
            'status' => 'idle',
            'capacity_per_hour' => 100,
            'hourly_rate' => 50,
        ]);
        Warehouse::query()->create(['code' => 'FG', 'name' => 'Finished', 'kind' => 'finished']);
        $raw = Warehouse::query()->create(['code' => 'RAW', 'name' => 'Raw', 'kind' => 'raw']);
        $resin = Material::query()->create([
            'sku' => 'RESIN',
            'name' => 'Resin',
            'category' => 'resin',
            'uom' => 'kg',
            'valuation_method' => 'fifo',
            'standard_cost' => 80,
            'min_qty' => 0,
            'reorder_qty' => 0,
            'is_active' => true,
        ]);
        app(StockService::class)->receive($resin, $raw, 'L1', 50, 80);

        $template = ProcessTemplate::query()->create(['button_family' => 'polyester_sheet', 'name' => 'Poly']);
        ProcessTemplateStage::query()->create([
            'process_template_id' => $template->id,
            'code' => 'mixing',
            'name' => 'Mixing',
            'sequence' => 1,
            'minutes_per_thousand' => 10,
        ]);
        ProcessTemplateStage::query()->create([
            'process_template_id' => $template->id,
            'code' => 'packaging',
            'name' => 'Packaging',
            'sequence' => 2,
            'minutes_per_thousand' => 5,
        ]);

        $product = Product::query()->create([
            'sku' => 'BTN-1',
            'name' => 'White 18L',
            'button_family' => 'polyester_sheet',
            'size_ligne' => 18,
            'thickness_mm' => 2.4,
            'hole_type' => 'four',
            'finish' => 'full_polish',
            'color' => 'White',
            'strategy' => 'mto',
            'list_price' => 2,
            'standard_cost' => 1,
        ]);
        $bom = Bom::query()->create(['product_id' => $product->id, 'version' => 1, 'status' => 'active']);
        BomItem::query()->create([
            'bom_id' => $bom->id,
            'component_type' => 'material',
            'material_id' => $resin->id,
            'quantity' => 1,
            'uom' => 'kg',
            'scrap_factor' => 0,
            'consume_at_stage' => 'mixing',
            'level' => 1,
        ]);

        $production = app(ProductionService::class);
        $order = $production->open($product->fresh(), 1000);
        $production->release($order);
        $stage = $order->stages()->where('code', 'mixing')->first();
        $production->start($stage, $operator, $machine);
        $production->complete($stage->fresh(), 1000, 0);

        $this->assertEquals(49, (float) $resin->fresh()->onHand());
        $this->assertSame('pending', $order->fresh()->stages()->where('code', 'packaging')->first()->status);

        app(IssueAutomationEngine::class)->report($stage->fresh(), 'color_mismatch', 'Shade is green against the swatch.');

        $this->assertSame('qc_hold', $order->fresh()->status);
        $this->assertSame('blocked', $order->stages()->where('code', 'packaging')->first()->status);
    }

    public function test_high_drilling_scrap_opens_a_calibration_ticket(): void
    {
        User::factory()->create(['role' => 'super_admin', 'is_active' => true]);
        $this->actingAs(User::factory()->create(['role' => 'qc', 'is_active' => true]));

        $template = ProcessTemplate::query()->create(['button_family' => 'polyester_sheet', 'name' => 'Poly']);
        ProcessTemplateStage::query()->create([
            'process_template_id' => $template->id,
            'code' => 'drilling',
            'name' => 'Drilling',
            'sequence' => 1,
            'minutes_per_thousand' => 10,
        ]);
        Machine::query()->create([
            'code' => 'DRL',
            'name' => 'Drill',
            'machine_type' => 'drilling',
            'status' => 'idle',
            'hourly_rate' => 10,
            'capacity_per_hour' => 10,
        ]);
        Warehouse::query()->create(['code' => 'FG', 'name' => 'Finished', 'kind' => 'finished']);
        $product = Product::query()->create([
            'sku' => 'BTN-2',
            'name' => 'Navy 20L',
            'button_family' => 'polyester_sheet',
            'size_ligne' => 20,
            'hole_type' => 'four',
            'finish' => 'full_polish',
            'color' => 'Navy',
            'strategy' => 'mto',
            'list_price' => 2,
        ]);
        $production = app(ProductionService::class);
        $order = $production->release($production->open($product, 1000));
        $stage = $order->stages()->first();
        $production->start($stage);
        $production->complete($stage->fresh(), 900, 100);

        $this->assertTrue(MaintenanceTicket::query()->where('title', 'like', 'Calibrate%')->exists());
        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_operator_cannot_delete_a_product_and_the_catalog_is_public(): void
    {
        $operator = User::factory()->create(['role' => 'operator', 'is_active' => true]);
        $product = Product::query()->create([
            'sku' => 'BTN-3',
            'name' => 'Public Ivory 16L',
            'button_family' => 'polyester_sheet',
            'size_ligne' => 16,
            'hole_type' => 'two',
            'finish' => 'matte',
            'color' => 'Ivory',
            'strategy' => 'mts',
            'list_price' => 1.5,
            'is_published' => true,
            'excerpt' => 'A shirt button.',
        ]);

        $this->actingAs($operator);
        $this->assertFalse($operator->can('delete', $product));

        $this->get('/catalog')->assertOk()->assertSee('Public Ivory 16L');
        $this->get('/catalog/'.$product->slug)->assertOk();
        $this->post('/inquiries', [
            'name' => 'Buyer',
            'email' => 'buyer@mill.test',
            'quantity' => 1000,
            'message' => 'Need ivory 16 ligne for a shirt program.',
            'product_id' => $product->id,
        ])->assertRedirect();
        $this->assertDatabaseHas('inquiries', ['email' => 'buyer@mill.test']);
    }
}
