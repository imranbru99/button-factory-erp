<?php

namespace Database\Seeders;

use App\Models\Bom;
use App\Models\BomItem;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeSkill;
use App\Models\Formula;
use App\Models\FormulaComponent;
use App\Models\Machine;
use App\Models\MaintenancePlan;
use App\Models\Material;
use App\Models\PriceBreak;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisitionLine;
use App\Models\ProcessTemplate;
use App\Models\ProcessTemplateStage;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Models\Shift;
use App\Models\SpcSample;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WorkCenter;
use App\Services\Catalog\CatalogCopyService;
use App\Services\Inventory\ReorderService;
use App\Services\Hr\AttendanceService;
use App\Services\Inventory\StockService;
use App\Services\Production\MrpService;
use App\Services\Production\ProductionService;
use Illuminate\Database\Seeder;

class ButtonFactorySeeder extends Seeder
{
    public function run(): void
    {
        $password = 'password';
        $users = collect([
            ['Super Admin', 'super@padmabutton.test', 'super_admin'],
            ['Karim Rahman', 'manager@padmabutton.test', 'production_manager'],
            ['Nusrat Jahan', 'qc@padmabutton.test', 'qc'],
            ['Jahidul Islam', 'operator@padmabutton.test', 'operator'],
            ['Sales Desk', 'sales@padmabutton.test', 'sales'],
            ['Accounts', 'accounts@padmabutton.test', 'accountant'],
            ['HR Desk', 'hr@padmabutton.test', 'hr'],
        ])->mapWithKeys(function (array $row) use ($password) {
            $user = User::query()->create([
                'name' => $row[0],
                'email' => $row[1],
                'password' => $password,
                'role' => $row[2],
                'locale' => 'en',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return [$row[2] => $user];
        });

        $shift = Shift::query()->create(['name' => 'Day', 'starts_at' => '08:00', 'ends_at' => '17:00', 'break_minutes' => 60]);
        Shift::query()->create(['name' => 'Evening', 'starts_at' => '14:00', 'ends_at' => '22:00', 'break_minutes' => 30]);

        $departments = collect([
            'MIX' => 'Mixing', 'CAST' => 'Casting', 'TURN' => 'Turning', 'DRILL' => 'Drilling',
            'POL' => 'Polishing', 'QC' => 'QC', 'PACK' => 'Packaging', 'MAINT' => 'Maintenance',
        ])->map(fn ($name, $code) => Department::query()->create(['code' => $code, 'name' => $name, 'name_bn' => $name]));

        $operator = Employee::query()->create([
            'user_id' => $users['operator']->id,
            'department_id' => $departments['DRILL']->id,
            'shift_id' => $shift->id,
            'employee_code' => 'E-014',
            'name' => 'Jahidul Islam',
            'name_bn' => 'জাহিদুল ইসলাম',
            'designation' => 'Drilling operator',
            'hourly_rate' => 180,
            'skill_level' => 'senior',
            'hired_on' => '2019-03-01',
            'phone' => '01711000014',
        ]);
        EmployeeSkill::query()->create(['employee_id' => $operator->id, 'skill' => 'drilling', 'level' => 5]);

        $crew = ['Mixing hand' => 'MIX', 'Caster' => 'CAST', 'Turner' => 'TURN', 'Polisher' => 'POL', 'QC inspector' => 'QC', 'Packer' => 'PACK', 'Fitter' => 'MAINT'];
        foreach (array_values(array_keys($crew)) as $index => $label) {
            $code = $crew[$label];
            $employee = Employee::query()->create([
                'department_id' => $departments[$code]->id,
                'shift_id' => $shift->id,
                'employee_code' => 'E-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'name' => $label,
                'designation' => $label,
                'hourly_rate' => 150 + ($index * 10),
                'skill_level' => 'operator',
                'hired_on' => '2021-01-15',
            ]);
            EmployeeSkill::query()->create(['employee_id' => $employee->id, 'skill' => strtolower($code), 'level' => 3]);
        }

        app(AttendanceService::class)->punch($operator, 'in');

        $raw = Warehouse::query()->create(['code' => 'RAW', 'name' => 'Raw store', 'kind' => 'raw', 'address' => 'Gazipur']);
        Warehouse::query()->create(['code' => 'FG', 'name' => 'Finished goods', 'kind' => 'finished', 'address' => 'Gazipur']);
        $raw->bins()->create(['code' => 'A-01', 'name' => 'Resin bay']);

        $materials = collect([
            ['RESIN-UPR', 'Unsaturated polyester resin', 'resin', 'kg', 'fifo', 200, 800, 120],
            ['CAT-MEKP', 'MEKP catalyst', 'catalyst', 'kg', 'fifo', 10, 40, 180],
            ['ACC-COB', 'Cobalt accelerator', 'accelerator', 'kg', 'fifo', 8, 30, 220],
            ['PIG-WHT', 'White pigment paste', 'pigment', 'kg', 'weighted_average', 15, 60, 90],
            ['PIG-BLK', 'Black pigment paste', 'pigment', 'kg', 'weighted_average', 10, 40, 95],
            ['PIG-NVY', 'Navy pigment paste', 'pigment', 'kg', 'weighted_average', 8, 30, 110],
            ['PEARL', 'Pearl essence', 'pearl', 'kg', 'fifo', 20, 5, 260],
            ['MEDIA', 'Polishing media', 'media', 'kg', 'fifo', 30, 100, 40],
            ['BAG', 'Poly bag', 'packaging', 'pcs', 'fifo', 500, 5000, 2],
            ['CTN', 'Export carton', 'packaging', 'pcs', 'fifo', 50, 400, 35],
            ['SHELL', 'Trochus shell blanks', 'shell', 'kg', 'fifo', 20, 80, 450],
        ])->mapWithKeys(function (array $row) {
            return [$row[0] => Material::query()->create([
                'sku' => $row[0],
                'name' => $row[1],
                'category' => $row[2],
                'uom' => $row[3],
                'valuation_method' => $row[4],
                'min_qty' => $row[2] === 'pearl' ? 20 : 5,
                'max_qty' => 1000,
                'reorder_qty' => 25,
                'standard_cost' => $row[7],
            ])];
        });

        $supplier = Supplier::query()->create([
            'code' => 'SUP-RESIN',
            'name' => 'Meghna Resin Ltd',
            'email' => 'sales@meghna-resin.test',
            'phone' => '02-550000',
            'city' => 'Chattogram',
            'lead_time_days' => 10,
        ]);

        $stock = app(StockService::class);
        foreach ($materials as $sku => $material) {
            $qty = $sku === 'PEARL' ? 4 : 400;
            $stock->receive($material, $raw, 'LOT-'.$sku.'-1', $qty, (float) $material->standard_cost, null, $supplier);
            if ($sku === 'PIG-WHT') {
                $stock->receive($material, $raw, 'LOT-'.$sku.'-2', 50, (float) $material->standard_cost + 10, null, $supplier);
            }
        }

        $centers = collect(['MIX' => 'Mixing', 'CAST' => 'Casting', 'TURN' => 'Turning', 'DRILL' => 'Drilling', 'POL' => 'Polishing', 'PACK' => 'Packing'])
            ->map(fn ($name, $code) => WorkCenter::query()->create([
                'code' => $code,
                'name' => $name,
                'department_id' => ($departments[$code] ?? $departments['PACK'])->id,
                'hours_per_day' => 8,
            ]));

        $machines = [
            ['CAST-01', 'Casting drum 1', 'casting', 'CAST', 800, 350],
            ['CAST-02', 'Casting drum 2', 'casting', 'CAST', 800, 350],
            ['ROD-01', 'Rod casting line', 'casting', 'CAST', 500, 400],
            ['BLNK-01', 'Blanking press', 'blanking', 'CAST', 2000, 220],
            ['TURN-01', 'Turning lathe 1', 'turning', 'TURN', 600, 280],
            ['TURN-02', 'Turning lathe 2', 'turning', 'TURN', 600, 280],
            ['TURN-03', 'Turning lathe 3', 'turning', 'TURN', 600, 280],
            ['DRL-01', 'Drilling machine 1', 'drilling', 'DRILL', 900, 240],
            ['DRL-02', 'Drilling machine 2', 'drilling', 'DRILL', 900, 240],
            ['DRL-03', 'Drilling machine 3', 'drilling', 'DRILL', 900, 240],
            ['POL-01', 'Polishing barrel 1', 'polishing', 'POL', 1500, 180],
            ['POL-02', 'Polishing barrel 2', 'polishing', 'POL', 1500, 180],
            ['LSR-01', 'Laser engraver', 'laser', 'POL', 300, 500],
            ['SHL-01', 'Shell punch press', 'punching', 'CAST', 700, 260],
        ];
        foreach ($machines as $row) {
            $machine = Machine::query()->create([
                'code' => $row[0],
                'name' => $row[1],
                'machine_type' => $row[2],
                'work_center_id' => $centers[$row[3]]->id ?? $centers['POL']->id,
                'department_id' => ($departments[$row[3]] ?? $departments['POL'])->id,
                'status' => 'idle',
                'capacity_per_hour' => $row[4],
                'hourly_rate' => $row[5],
                'commissioned_on' => '2020-06-01',
            ]);
            MaintenancePlan::query()->create([
                'machine_id' => $machine->id,
                'task' => 'Lubricate and check tooling',
                'interval_days' => 30,
                'last_done_on' => now()->subDays(20)->toDateString(),
                'next_due_on' => now()->addDays(10)->toDateString(),
            ]);
        }

        $this->templates();

        $shirt = Category::query()->create(['name' => 'Shirt buttons', 'name_bn' => 'শার্টের বোতাম', 'description' => 'Everyday polyester shirt buttons.']);
        $coat = Category::query()->create(['name' => 'Coat buttons', 'name_bn' => 'কোটের বোতাম', 'description' => 'Heavier coat and blazer buttons.']);
        $shellCat = Category::query()->create(['name' => 'Shell buttons', 'name_bn' => 'শেল বোতাম', 'description' => 'Natural shell.']);
        $special = Category::query()->create(['name' => 'Specialty', 'name_bn' => 'বিশেষ বোতাম', 'description' => 'Metal, wood, and laser.']);

        $catalog = [
            ['polyester_sheet', $shirt, 14, 'four', 'full_polish', 'White', 'PIG-WHT'],
            ['polyester_sheet', $shirt, 16, 'four', 'full_polish', 'White', 'PIG-WHT'],
            ['polyester_sheet', $shirt, 18, 'four', 'full_polish', 'White', 'PIG-WHT'],
            ['polyester_sheet', $shirt, 18, 'two', 'matte', 'Black', 'PIG-BLK'],
            ['polyester_sheet', $shirt, 20, 'four', 'full_polish', 'Navy', 'PIG-NVY'],
            ['polyester_sheet', $shirt, 22, 'four', 'semi', 'White', 'PIG-WHT'],
            ['polyester_sheet', $shirt, 24, 'four', 'full_polish', 'Black', 'PIG-BLK'],
            ['polyester_rod', $coat, 24, 'four', 'full_polish', 'Navy', 'PIG-NVY'],
            ['polyester_rod', $coat, 28, 'four', 'full_polish', 'Black', 'PIG-BLK'],
            ['polyester_rod', $coat, 32, 'shank', 'full_polish', 'Navy', 'PIG-NVY'],
            ['polyester_rod', $coat, 36, 'shank', 'full_polish', 'Black', 'PIG-BLK'],
            ['polyester_rod', $coat, 40, 'shank', 'matte', 'White', 'PIG-WHT'],
            ['shell', $shellCat, 18, 'two', 'full_polish', 'Natural', 'SHELL'],
            ['shell', $shellCat, 20, 'four', 'full_polish', 'White', 'SHELL'],
            ['shell', $shellCat, 22, 'two', 'semi', 'Natural', 'SHELL'],
            ['metal', $special, 24, 'shank', 'full_polish', 'Gold', 'SHELL'],
            ['metal', $special, 28, 'shank', 'full_polish', 'Antique', 'SHELL'],
            ['metal', $special, 36, 'shank', 'matte', 'Gunmetal', 'SHELL'],
            ['wooden', $special, 20, 'two', 'matte', 'Walnut', 'SHELL'],
            ['wooden', $special, 24, 'four', 'semi', 'Oak', 'SHELL'],
            ['specialty', $special, 18, 'four', 'laser_engraved', 'White', 'PIG-WHT'],
            ['specialty', $special, 20, 'four', 'laser_engraved', 'Black', 'PIG-BLK'],
            ['polyester_sheet', $shirt, 14, 'two', 'semi', 'Red', 'PIG-WHT'],
            ['polyester_sheet', $shirt, 16, 'four', 'matte', 'Grey', 'PIG-BLK'],
            ['polyester_sheet', $shirt, 18, 'four', 'full_polish', 'Ivory', 'PIG-WHT'],
            ['polyester_sheet', $shirt, 20, 'two', 'full_polish', 'Brown', 'PIG-BLK'],
            ['polyester_rod', $coat, 30, 'four', 'full_polish', 'Burgundy', 'PIG-NVY'],
            ['polyester_rod', $coat, 34, 'shank', 'semi', 'Forest', 'PIG-NVY'],
            ['shell', $shellCat, 16, 'two', 'full_polish', 'Cream', 'SHELL'],
            ['shell', $shellCat, 24, 'four', 'full_polish', 'Natural', 'SHELL'],
            ['specialty', $special, 22, 'shank', 'laser_engraved', 'Navy', 'PIG-NVY'],
            ['wooden', $special, 18, 'two', 'matte', 'Teak', 'SHELL'],
        ];

        $formulas = $this->formulas($materials);
        $products = [];
        foreach ($catalog as $index => $row) {
            [$family, $category, $size, $hole, $finish, $color, $pigment] = $row;
            $name = ucfirst(str_replace('_', ' ', $family))." {$size}L {$color}";
            $product = Product::query()->create([
                'category_id' => $category->id,
                'sku' => 'BTN-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'name' => $name,
                'name_bn' => "{$size} লাইন {$color} বোতাম",
                'button_family' => $family,
                'size_ligne' => $size,
                'thickness_mm' => $size >= 28 ? 3.2 : 2.4,
                'hole_type' => $hole,
                'design_pattern' => $finish === 'laser_engraved' ? 'engraved' : 'plain',
                'finish' => $finish,
                'color' => $color,
                'material_label' => str_replace('_', ' ', $family),
                'strategy' => $index % 5 === 0 ? 'mts' : 'mto',
                'standard_cost' => 0.8 + ($size / 40),
                'list_price' => 1.6 + ($size / 20),
                'min_stock' => 1000,
                'reorder_qty' => 5000,
                'locale' => 'en',
            ]);
            app(CatalogCopyService::class)->generate($product);
            PriceBreak::query()->create(['product_id' => $product->id, 'min_qty' => 1000, 'finish' => $finish, 'unit_price' => $product->list_price]);
            PriceBreak::query()->create(['product_id' => $product->id, 'min_qty' => 10000, 'finish' => $finish, 'unit_price' => round((float) $product->list_price * 0.82, 4)]);
            $this->bom($product, $materials, $pigment, $family === 'polyester_sheet' || $family === 'polyester_rod' ? $formulas[$color] ?? $formulas['White'] : null);
            $products[] = $product;
        }

        $customer = Customer::query()->create([
            'code' => 'CUS-HAQUE',
            'name' => 'Haque Garments',
            'company' => 'Haque Garments Ltd',
            'email' => 'buying@haque.test',
            'phone' => '01700000001',
            'city' => 'Dhaka',
            'address' => 'Mirpur',
        ]);

        $order = SalesOrder::query()->create([
            'customer_id' => $customer->id,
            'status' => 'confirmed',
            'strategy' => 'mto',
            'ordered_on' => now()->toDateString(),
            'promised_on' => now()->addDays(12)->toDateString(),
            'currency' => 'BDT',
            'notes' => 'Shirt program, white 18L.',
        ]);
        SalesOrderLine::query()->create([
            'sales_order_id' => $order->id,
            'product_id' => $products[2]->id,
            'qty' => 2000,
            'unit_price' => $products[2]->priceFor(2000, $products[2]->finish),
            'finish' => $products[2]->finish,
        ]);

        $plan = app(MrpService::class)->plan($order->fresh());
        $workOrder = $plan['work_orders'][0] ?? null;
        if ($workOrder) {
            $stage = $workOrder->stages()->orderBy('sequence')->first();
            $drill = Machine::query()->where('code', 'DRL-01')->first();
            $production = app(ProductionService::class);
            $production->start($stage, $operator, $drill);
            $production->complete($stage, 1980, 20);
            SpcSample::query()->create([
                'work_order_stage_id' => $stage->id,
                'characteristic' => 'thickness_mm',
                'value' => 2.41,
                'sampled_at' => now(),
            ]);
            $white = Formula::query()->where('code', 'F-White')->where('status', 'active')->first();
            if ($white) {
                $workOrder->update(['formula_id' => $white->id]);
            }
        }

        app(ReorderService::class)->scan();
        $this->requestPearlQuote($supplier);
    }

    private function requestPearlQuote(Supplier $supplier): void
    {
        $pearl = Material::query()->where('sku', 'PEARL')->first();
        $line = $pearl
            ? PurchaseRequisitionLine::query()->where('material_id', $pearl->id)->first()
            : null;

        if (! $line || PurchaseOrder::query()->where('purchase_requisition_id', $line->purchase_requisition_id)->exists()) {
            return;
        }

        $order = PurchaseOrder::query()->create([
            'supplier_id' => $supplier->id,
            'purchase_requisition_id' => $line->purchase_requisition_id,
            'status' => 'rfq',
            'ordered_on' => now()->toDateString(),
            'expected_on' => now()->addDays((int) $supplier->lead_time_days)->toDateString(),
            'currency' => 'BDT',
            'notes' => 'RFQ sent to the supplier. Issue the purchase order after the quote is accepted.',
        ]);
        $order->lines()->create([
            'material_id' => $pearl->id,
            'qty' => $line->qty,
            'unit_cost' => $pearl->standard_cost,
        ]);
    }

    private function templates(): void
    {
        foreach (['polyester_sheet' => 'polyester_stages', 'polyester_rod' => 'polyester_stages', 'metal' => 'polyester_stages', 'wooden' => 'polyester_stages', 'specialty' => 'polyester_stages', 'shell' => 'shell_stages'] as $family => $key) {
            $template = ProcessTemplate::query()->create(['button_family' => $family, 'name' => str_replace('_', ' ', $family)]);
            foreach (config('factory.'.$key) as $index => $stage) {
                ProcessTemplateStage::query()->create([
                    'process_template_id' => $template->id,
                    'code' => $stage['code'],
                    'name' => $stage['name'],
                    'sequence' => $index + 1,
                    'minutes_per_thousand' => $stage['minutes_per_thousand'],
                ]);
            }
        }
    }

    /** @return array<string, Formula> */
    private function formulas($materials): array
    {
        $map = ['White' => 'PIG-WHT', 'Black' => 'PIG-BLK', 'Navy' => 'PIG-NVY'];
        $formulas = [];
        foreach ($map as $color => $pigment) {
            $old = Formula::query()->create([
                'code' => 'F-'.$color,
                'name' => $color.' polyester v1',
                'version' => 1,
                'status' => 'obsolete',
                'effective_from' => now()->subYear()->toDateString(),
            ]);
            $formula = Formula::query()->create([
                'code' => 'F-'.$color,
                'name' => $color.' polyester',
                'version' => 2,
                'status' => 'active',
                'effective_from' => now()->subMonth()->toDateString(),
                'supersedes_id' => $old->id,
                'notes' => 'Shop-floor standard. Percentages are of batch weight.',
            ]);
            foreach ([['RESIN-UPR', 'resin', 96], ['CAT-MEKP', 'catalyst', 1.5], ['ACC-COB', 'accelerator', 1.0], [$pigment, 'pigment', 1.5]] as $part) {
                FormulaComponent::query()->create([
                    'formula_id' => $formula->id,
                    'material_id' => $materials[$part[0]]->id,
                    'role' => $part[1],
                    'percentage' => $part[2],
                ]);
            }
            $formulas[$color] = $formula;
        }

        return $formulas;
    }

    private function bom(Product $product, $materials, string $pigment, ?Formula $formula): void
    {
        $bom = Bom::query()->create([
            'product_id' => $product->id,
            'version' => 1,
            'status' => 'active',
            'notes' => 'Quantities are per 1,000 buttons.',
        ]);
        $lines = $product->button_family === 'shell'
            ? [['SHELL', 0.4, 'kg', 'shell_selection'], ['MEDIA', 0.05, 'kg', 'polishing_bleaching'], ['BAG', 20, 'pcs', 'packaging'], ['CTN', 1, 'pcs', 'packaging']]
            : [['RESIN-UPR', 1.2, 'kg', 'mixing'], [$pigment, 0.02, 'kg', 'mixing'], ['MEDIA', 0.05, 'kg', 'polishing'], ['BAG', 20, 'pcs', 'packaging'], ['CTN', 1, 'pcs', 'packaging']];

        $parent = null;
        foreach ($lines as $index => $line) {
            $item = BomItem::query()->create([
                'bom_id' => $bom->id,
                'parent_id' => $index === 0 ? null : $parent,
                'component_type' => in_array($line[0], ['BAG', 'CTN'], true) ? 'packaging' : 'material',
                'material_id' => $materials[$line[0]]->id,
                'quantity' => $line[1],
                'uom' => $line[2],
                'scrap_factor' => 0.02,
                'consume_at_stage' => $line[3],
                'level' => $index === 0 ? 1 : 2,
            ]);
            $parent = $parent ?? $item->id;
        }
    }
}
