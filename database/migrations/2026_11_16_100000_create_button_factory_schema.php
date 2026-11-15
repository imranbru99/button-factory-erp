<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_counters', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->unsignedInteger('value')->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('operator')->index();
            $table->string('phone')->nullable();
            $table->string('locale', 5)->default('en');
            $table->boolean('is_active')->default(true);
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->timestamps();
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('starts_at');
            $table->time('ends_at');
            $table->unsignedSmallInteger('break_minutes')->default(30);
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('designation');
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->string('skill_level')->default('operator');
            $table->date('hired_on');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('skill');
            $table->unsignedTinyInteger('level')->default(1);
            $table->timestamps();
            $table->unique(['employee_id', 'skill']);
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->string('type');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->string('status')->default('pending')->index();
            $table->text('reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('overtime_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->date('work_date');
            $table->decimal('hours', 6, 2);
            $table->decimal('rate_multiplier', 4, 2)->default(1.5);
            $table->string('status')->default('pending');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->string('slug_bn')->nullable()->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('kind')->default('raw')->index();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('bins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->timestamps();
            $table->unique(['warehouse_id', 'code']);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->default('Bangladesh');
            $table->string('city')->nullable();
            $table->unsignedSmallInteger('lead_time_days')->default(14);
            $table->timestamps();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('category')->index();
            $table->string('uom')->default('kg');
            $table->string('valuation_method')->default('fifo');
            $table->decimal('min_qty', 14, 3)->default(0);
            $table->decimal('max_qty', 14, 3)->default(0);
            $table->decimal('reorder_qty', 14, 3)->default(0);
            $table->decimal('standard_cost', 12, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->string('slug_bn')->nullable()->unique();
            $table->string('button_family')->index();
            $table->unsignedSmallInteger('size_ligne')->index();
            $table->decimal('thickness_mm', 6, 2)->default(0);
            $table->string('hole_type')->default('four');
            $table->string('design_pattern')->default('plain');
            $table->string('finish')->default('full_polish')->index();
            $table->string('color');
            $table->string('color_code')->nullable();
            $table->string('material_label')->nullable();
            $table->string('strategy')->default('mto')->index();
            $table->decimal('standard_cost', 12, 4)->default(0);
            $table->decimal('list_price', 12, 4)->default(0);
            $table->decimal('min_stock', 14, 3)->default(0);
            $table->decimal('reorder_qty', 14, 3)->default(0);
            $table->boolean('is_published')->default(false)->index();
            $table->string('image_path')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->json('attributes')->nullable();
            $table->json('tags')->nullable();
            $table->json('faq')->nullable();
            $table->text('care_instructions')->nullable();
            $table->json('size_guide')->nullable();
            $table->string('gtin')->nullable();
            $table->string('mpn')->nullable();
            $table->json('json_ld')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('translations')->nullable();
            $table->string('locale', 5)->default('en');
            $table->timestamps();
        });

        Schema::create('formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('status')->default('draft')->index();
            $table->text('notes')->nullable();
            $table->date('effective_from')->nullable();
            $table->foreignId('supersedes_id')->nullable()->constrained('formulas')->nullOnDelete();
            $table->timestamps();
            $table->unique(['code', 'version']);
        });

        Schema::create('formula_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formula_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained();
            $table->string('role');
            $table->decimal('percentage', 8, 3);
            $table->timestamps();
        });

        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->string('status')->default('draft')->index();
            $table->text('notes')->nullable();
            $table->foreignId('supersedes_id')->nullable()->constrained('boms')->nullOnDelete();
            $table->timestamps();
            $table->unique(['product_id', 'version']);
        });

        Schema::create('bom_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('bom_items')->nullOnDelete();
            $table->string('component_type');
            $table->foreignId('material_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('component_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->decimal('quantity', 14, 4);
            $table->string('uom')->default('kg');
            $table->decimal('scrap_factor', 8, 4)->default(0);
            $table->string('consume_at_stage')->nullable();
            $table->unsignedSmallInteger('level')->default(1);
            $table->timestamps();
        });

        Schema::create('price_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('min_qty');
            $table->string('finish')->nullable();
            $table->decimal('unit_price', 12, 4);
            $table->timestamps();
            $table->index(['product_id', 'min_qty']);
        });

        Schema::create('process_templates', function (Blueprint $table) {
            $table->id();
            $table->string('button_family')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('process_template_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_template_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->unsignedSmallInteger('sequence');
            $table->decimal('minutes_per_thousand', 8, 2)->default(20);
            $table->string('work_center_code')->nullable();
            $table->timestamps();
        });

        Schema::create('work_centers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('hours_per_day', 5, 2)->default(8);
            $table->timestamps();
        });

        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('machine_type')->index();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('idle')->index();
            $table->decimal('capacity_per_hour', 12, 2)->default(0);
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->date('commissioned_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('maintenance_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
            $table->string('task');
            $table->unsignedSmallInteger('interval_days')->default(30);
            $table->date('last_done_on')->nullable();
            $table->date('next_due_on')->index();
            $table->timestamps();
        });

        Schema::create('maintenance_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('machine_id')->constrained();
            $table->string('priority')->default('medium')->index();
            $table->string('status')->default('open')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('suggested_solution')->nullable();
            $table->string('source')->default('manual');
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('bin_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('lot_number');
            $table->decimal('qty_on_hand', 14, 3)->default(0);
            $table->decimal('unit_cost', 12, 4)->default(0);
            $table->timestamp('received_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->string('status')->default('available')->index();
            $table->timestamps();
            $table->unique(['material_id', 'warehouse_id', 'lot_number']);
            $table->index(['material_id', 'received_at']);
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained();
            $table->foreignId('lot_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('warehouse_id')->constrained();
            $table->string('type')->index();
            $table->decimal('qty', 14, 3);
            $table->decimal('unit_cost', 12, 4)->default(0);
            $table->nullableMorphs('reference');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index(['material_id', 'created_at']);
        });

        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained();
            $table->decimal('qty', 14, 3)->default(0);
            $table->timestamps();
            $table->unique(['product_id', 'warehouse_id']);
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->default('Bangladesh');
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('payment_terms')->default('30 days');
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->text('message');
            $table->string('locale', 5)->default('en');
            $table->string('status')->default('new')->index();
            $table->timestamps();
        });

        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('inquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft')->index();
            $table->date('valid_until')->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('quotation_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('qty');
            $table->decimal('unit_price', 12, 4);
            $table->string('finish')->nullable();
            $table->unsignedSmallInteger('size_ligne')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('quotation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft')->index();
            $table->string('strategy')->default('mto');
            $table->date('ordered_on');
            $table->date('promised_on')->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('qty');
            $table->unsignedInteger('qty_delivered')->default(0);
            $table->decimal('unit_price', 12, 4);
            $table->string('finish')->nullable();
            $table->timestamps();
        });

        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('sales_order_line_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('bom_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('formula_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->boolean('is_rework')->default(false);
            $table->decimal('qty_planned', 14, 3);
            $table->decimal('qty_completed', 14, 3)->default(0);
            $table->decimal('qty_scrapped', 14, 3)->default(0);
            $table->string('status')->default('planned')->index();
            $table->string('strategy')->default('mto');
            $table->unsignedTinyInteger('priority')->default(3);
            $table->timestamp('scheduled_start')->nullable()->index();
            $table->timestamp('scheduled_end')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('work_order_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->string('code')->index();
            $table->string('name');
            $table->unsignedSmallInteger('sequence');
            $table->string('status')->default('pending')->index();
            $table->foreignId('operator_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('machine_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->decimal('expected_minutes', 10, 2)->default(0);
            $table->decimal('qty_in', 14, 3)->default(0);
            $table->decimal('qty_out', 14, 3)->default(0);
            $table->decimal('qty_scrap', 14, 3)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['work_order_id', 'sequence']);
        });

        Schema::create('machine_runtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->decimal('planned_minutes', 10, 2)->default(0);
            $table->decimal('downtime_minutes', 10, 2)->default(0);
            $table->decimal('good_qty', 14, 3)->default(0);
            $table->decimal('total_qty', 14, 3)->default(0);
            $table->timestamps();
        });

        Schema::create('stage_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_stage_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->string('defect_code')->index();
            $table->string('severity')->default('medium');
            $table->text('description');
            $table->string('photo_path')->nullable();
            $table->string('status')->default('open')->index();
            $table->json('ai_analysis')->nullable();
            $table->json('suggested_actions')->nullable();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('sales_order_id')->constrained();
            $table->string('status')->default('draft');
            $table->date('shipped_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sales_order_line_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('qty');
            $table->timestamps();
        });

        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('status')->default('open')->index();
            $table->string('source')->default('manual');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('needed_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_requisition_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_requisition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained();
            $table->decimal('qty', 14, 3);
            $table->decimal('estimated_cost', 12, 4)->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('purchase_requisition_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft')->index();
            $table->date('ordered_on')->nullable();
            $table->date('expected_on')->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained();
            $table->decimal('qty', 14, 3);
            $table->decimal('qty_received', 14, 3)->default(0);
            $table->decimal('unit_cost', 12, 4);
            $table->timestamps();
        });

        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->date('received_on');
            $table->string('invoice_path')->nullable();
            $table->json('ocr_payload')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('goods_receipt_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_order_line_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('material_id')->constrained();
            $table->foreignId('lot_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('qty', 14, 3);
            $table->decimal('unit_cost', 12, 4);
            $table->timestamps();
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('type')->index();
            $table->nullableMorphs('inspectable');
            $table->string('result')->default('pending')->index();
            $table->foreignId('inspector_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('sampled_qty', 14, 3)->default(0);
            $table->decimal('rejected_qty', 14, 3)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('defects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->cascadeOnDelete();
            $table->string('defect_code')->index();
            $table->decimal('qty', 14, 3)->default(0);
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ncrs', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('inspection_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('open')->index();
            $table->text('description');
            $table->string('disposition')->nullable();
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('capas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ncr_id')->constrained()->cascadeOnDelete();
            $table->text('action');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('due_on')->nullable();
            $table->string('status')->default('open');
            $table->text('effectiveness_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('spc_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_stage_id')->nullable()->constrained()->nullOnDelete();
            $table->string('characteristic');
            $table->decimal('value', 12, 4);
            $table->timestamp('sampled_at');
            $table->timestamps();
            $table->index(['characteristic', 'sampled_at']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('sales_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft')->index();
            $table->date('issued_on');
            $table->date('due_on')->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('qty', 14, 3);
            $table->decimal('unit_price', 12, 4);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable');
            $table->string('direction')->index();
            $table->decimal('amount', 14, 2);
            $table->date('paid_on');
            $table->string('method')->default('bank');
            $table->string('reference')->nullable();
            $table->timestamps();
        });

        Schema::create('job_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('material_cost', 14, 2)->default(0);
            $table->decimal('labor_cost', 14, 2)->default(0);
            $table->decimal('machine_cost', 14, 2)->default(0);
            $table->decimal('overhead_cost', 14, 2)->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('unit_cost', 12, 4)->default(0);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('trigger');
            $table->string('rule');
            $table->nullableMorphs('subject');
            $table->string('action');
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('auditable');
            $table->string('event');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('automation_logs');
        Schema::dropIfExists('job_costs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('spc_samples');
        Schema::dropIfExists('capas');
        Schema::dropIfExists('ncrs');
        Schema::dropIfExists('defects');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('goods_receipt_lines');
        Schema::dropIfExists('goods_receipts');
        Schema::dropIfExists('purchase_order_lines');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_requisition_lines');
        Schema::dropIfExists('purchase_requisitions');
        Schema::dropIfExists('delivery_lines');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('stage_issues');
        Schema::dropIfExists('machine_runtimes');
        Schema::dropIfExists('work_order_stages');
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('sales_order_lines');
        Schema::dropIfExists('sales_orders');
        Schema::dropIfExists('quotation_lines');
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('product_stocks');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('lots');
        Schema::dropIfExists('maintenance_tickets');
        Schema::dropIfExists('maintenance_plans');
        Schema::dropIfExists('machines');
        Schema::dropIfExists('work_centers');
        Schema::dropIfExists('process_template_stages');
        Schema::dropIfExists('process_templates');
        Schema::dropIfExists('price_breaks');
        Schema::dropIfExists('bom_items');
        Schema::dropIfExists('boms');
        Schema::dropIfExists('formula_components');
        Schema::dropIfExists('formulas');
        Schema::dropIfExists('products');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('bins');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('overtime_entries');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('employee_skills');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('document_counters');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'locale', 'is_active']);
        });
    }
};
