#!/usr/bin/env python3
"""Generate factory models, policies, factories, and Filament resources."""
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

AUDITED = {
    "Product", "Formula", "Bom", "WorkOrder", "SalesOrder", "PurchaseOrder",
    "Invoice", "Inspection", "Ncr", "Machine", "GoodsReceipt",
}
NUMBERED = {
    "WorkOrder": "WO", "SalesOrder": "SO", "Quotation": "QT", "PurchaseOrder": "PO",
    "PurchaseRequisition": "PR", "GoodsReceipt": "GR", "Invoice": "INV",
    "Delivery": "DN", "Ncr": "NCR", "Inspection": "QC", "MaintenanceTicket": "MT",
}
RICH = {
    "Product", "Formula", "Bom", "SalesOrder", "Quotation", "PurchaseOrder",
    "GoodsReceipt", "WorkOrder", "Invoice", "Inspection", "Ncr", "Machine", "Employee",
}
RELATIONS = {
    "Formula": [("components", "FormulaComponentsRelationManager", "components")],
    "Bom": [("items", "BomItemsRelationManager", "items")],
    "Quotation": [("lines", "QuotationLinesRelationManager", "lines")],
    "SalesOrder": [("lines", "SalesOrderLinesRelationManager", "lines")],
    "PurchaseOrder": [("lines", "PurchaseOrderLinesRelationManager", "lines")],
    "GoodsReceipt": [("lines", "GoodsReceiptLinesRelationManager", "lines")],
    "WorkOrder": [("stages", "WorkOrderStagesRelationManager", "stages")],
    "Invoice": [("lines", "InvoiceLinesRelationManager", "lines")],
    "Inspection": [("defects", "DefectsRelationManager", "defects")],
    "Ncr": [("capas", "CapasRelationManager", "capas")],
}

# field: (cast or None, component, required, relation title column or None)
# component: string, text, bool, int, decimal, date, datetime, json, tags, belongs

MODELS = []

def M(name, table, module, group, icon, fields, rels, extra=""):
    MODELS.append(dict(name=name, table=table, module=module, group=group, icon=icon, fields=fields, rels=rels, extra=extra))

M("Department", "departments", "hr", "People", "heroicon-o-building-office-2", [
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("name_bn", None, "string", False, None),
], [("hasMany", "employees", "Employee", "department_id"), ("hasMany", "machines", "Machine", "department_id")])

M("Shift", "shifts", "hr", "People", "heroicon-o-clock", [
    ("name", None, "string", True, None),
    ("starts_at", None, "string", True, None),
    ("ends_at", None, "string", True, None),
    ("break_minutes", "integer", "int", True, None),
], [("hasMany", "employees", "Employee", "shift_id")])

M("Employee", "employees", "hr", "People", "heroicon-o-user-group", [
    ("user_id", None, "belongs", False, ("user", "User", "name")),
    ("department_id", None, "belongs", True, ("department", "Department", "name")),
    ("shift_id", None, "belongs", False, ("shift", "Shift", "name")),
    ("employee_code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("name_bn", None, "string", False, None),
    ("designation", None, "string", True, None),
    ("hourly_rate", "decimal:2", "decimal", True, None),
    ("skill_level", None, "string", True, None),
    ("hired_on", "date", "date", True, None),
    ("phone", None, "string", False, None),
    ("is_active", "boolean", "bool", False, None),
], [
    ("hasMany", "skills", "EmployeeSkill", "employee_id"),
    ("hasMany", "leaveRequests", "LeaveRequest", "employee_id"),
    ("hasMany", "overtimeEntries", "OvertimeEntry", "employee_id"),
    ("hasMany", "stages", "WorkOrderStage", "operator_id"),
])

M("EmployeeSkill", "employee_skills", "hr", "People", "heroicon-o-academic-cap", [
    ("employee_id", None, "belongs", True, ("employee", "Employee", "name")),
    ("skill", None, "string", True, None),
    ("level", "integer", "int", True, None),
], [])

M("LeaveRequest", "leave_requests", "hr", "People", "heroicon-o-calendar-days", [
    ("employee_id", None, "belongs", True, ("employee", "Employee", "name")),
    ("type", None, "string", True, None),
    ("starts_on", "date", "date", True, None),
    ("ends_on", "date", "date", True, None),
    ("status", None, "string", True, None),
    ("reason", None, "text", False, None),
    ("approved_by", None, "belongs", False, ("approver", "User", "name")),
], [])

M("OvertimeEntry", "overtime_entries", "hr", "People", "heroicon-o-bolt", [
    ("employee_id", None, "belongs", True, ("employee", "Employee", "name")),
    ("work_date", "date", "date", True, None),
    ("hours", "decimal:2", "decimal", True, None),
    ("rate_multiplier", "decimal:2", "decimal", True, None),
    ("status", None, "string", True, None),
    ("notes", None, "string", False, None),
], [])

M("Category", "categories", "catalog", "Catalog", "heroicon-o-tag", [
    ("name", None, "string", True, None),
    ("name_bn", None, "string", False, None),
    ("slug", None, "string", False, None),
    ("slug_bn", None, "string", False, None),
    ("parent_id", None, "belongs", False, ("parent", "Category", "name")),
    ("description", None, "text", False, None),
], [("hasMany", "children", "Category", "parent_id"), ("hasMany", "products", "Product", "category_id")], extra="SLUG")

M("Warehouse", "warehouses", "inventory", "Inventory", "heroicon-o-building-storefront", [
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("kind", None, "string", True, None),
    ("address", None, "string", False, None),
], [("hasMany", "bins", "Bin", "warehouse_id"), ("hasMany", "lots", "Lot", "warehouse_id")])

M("Bin", "bins", "inventory", "Inventory", "heroicon-o-square-3-stack-3d", [
    ("warehouse_id", None, "belongs", True, ("warehouse", "Warehouse", "name")),
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
], [])

M("Supplier", "suppliers", "procurement", "Procurement", "heroicon-o-truck", [
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("email", None, "string", False, None),
    ("phone", None, "string", False, None),
    ("country", None, "string", False, None),
    ("city", None, "string", False, None),
    ("lead_time_days", "integer", "int", True, None),
], [("hasMany", "purchaseOrders", "PurchaseOrder", "supplier_id")])

M("Material", "materials", "inventory", "Inventory", "heroicon-o-beaker", [
    ("sku", None, "string", True, None),
    ("name", None, "string", True, None),
    ("category", None, "string", True, None),
    ("uom", None, "string", True, None),
    ("valuation_method", None, "string", True, None),
    ("min_qty", "decimal:3", "decimal", True, None),
    ("max_qty", "decimal:3", "decimal", True, None),
    ("reorder_qty", "decimal:3", "decimal", True, None),
    ("standard_cost", "decimal:4", "decimal", True, None),
    ("is_active", "boolean", "bool", False, None),
], [("hasMany", "lots", "Lot", "material_id"), ("hasMany", "movements", "StockMovement", "material_id")],
   extra="MATERIAL")

M("Product", "products", "catalog", "Catalog", "heroicon-o-stop", [
    ("category_id", None, "belongs", False, ("category", "Category", "name")),
    ("sku", None, "string", True, None),
    ("name", None, "string", True, None),
    ("name_bn", None, "string", False, None),
    ("slug", None, "string", False, None),
    ("slug_bn", None, "string", False, None),
    ("button_family", None, "string", True, None),
    ("size_ligne", "integer", "int", True, None),
    ("thickness_mm", "decimal:2", "decimal", True, None),
    ("hole_type", None, "string", True, None),
    ("design_pattern", None, "string", True, None),
    ("finish", None, "string", True, None),
    ("color", None, "string", True, None),
    ("color_code", None, "string", False, None),
    ("material_label", None, "string", False, None),
    ("strategy", None, "string", True, None),
    ("standard_cost", "decimal:4", "decimal", True, None),
    ("list_price", "decimal:4", "decimal", True, None),
    ("min_stock", "decimal:3", "decimal", False, None),
    ("reorder_qty", "decimal:3", "decimal", False, None),
    ("is_published", "boolean", "bool", False, None),
    ("image_path", None, "string", False, None),
    ("excerpt", None, "string", False, None),
    ("description", None, "text", False, None),
    ("description_bn", None, "text", False, None),
    ("meta_title", None, "string", False, None),
    ("meta_description", None, "string", False, None),
    ("attributes", "array", "json", False, None),
    ("tags", "array", "tags", False, None),
    ("faq", "array", "json", False, None),
    ("care_instructions", None, "text", False, None),
    ("size_guide", "array", "json", False, None),
    ("gtin", None, "string", False, None),
    ("mpn", None, "string", False, None),
    ("json_ld", "array", "json", False, None),
    ("image_alt", None, "string", False, None),
    ("translations", "array", "json", False, None),
    ("locale", None, "string", False, None),
], [
    ("hasMany", "formulas", "Formula", "product_id"),
    ("hasMany", "boms", "Bom", "product_id"),
    ("hasMany", "priceBreaks", "PriceBreak", "product_id"),
    ("hasMany", "stocks", "ProductStock", "product_id"),
    ("hasMany", "workOrders", "WorkOrder", "product_id"),
], extra="PRODUCT")

M("Formula", "formulas", "catalog", "Catalog", "heroicon-o-beaker", [
    ("product_id", None, "belongs", False, ("product", "Product", "name")),
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("version", "integer", "int", True, None),
    ("status", None, "string", True, None),
    ("notes", None, "text", False, None),
    ("effective_from", "date", "date", False, None),
    ("supersedes_id", None, "belongs", False, ("supersedes", "Formula", "name")),
], [("hasMany", "components", "FormulaComponent", "formula_id")])

M("FormulaComponent", "formula_components", "catalog", "Catalog", "heroicon-o-swatch", [
    ("formula_id", None, "belongs", True, ("formula", "Formula", "name")),
    ("material_id", None, "belongs", True, ("material", "Material", "name")),
    ("role", None, "string", True, None),
    ("percentage", "decimal:3", "decimal", True, None),
], [])

M("Bom", "boms", "catalog", "Catalog", "heroicon-o-queue-list", [
    ("product_id", None, "belongs", True, ("product", "Product", "name")),
    ("version", "integer", "int", True, None),
    ("status", None, "string", True, None),
    ("notes", None, "text", False, None),
    ("supersedes_id", None, "belongs", False, ("supersedes", "Bom", "id")),
], [("hasMany", "items", "BomItem", "bom_id")])

M("BomItem", "bom_items", "catalog", "Catalog", "heroicon-o-list-bullet", [
    ("bom_id", None, "belongs", True, ("bom", "Bom", "id")),
    ("parent_id", None, "belongs", False, ("parent", "BomItem", "id")),
    ("component_type", None, "string", True, None),
    ("material_id", None, "belongs", False, ("material", "Material", "name")),
    ("component_product_id", None, "belongs", False, ("componentProduct", "Product", "name")),
    ("quantity", "decimal:4", "decimal", True, None),
    ("uom", None, "string", True, None),
    ("scrap_factor", "decimal:4", "decimal", False, None),
    ("consume_at_stage", None, "string", False, None),
    ("level", "integer", "int", False, None),
], [("hasMany", "children", "BomItem", "parent_id")])

M("PriceBreak", "price_breaks", "sales", "Sales", "heroicon-o-banknotes", [
    ("product_id", None, "belongs", True, ("product", "Product", "name")),
    ("min_qty", "integer", "int", True, None),
    ("finish", None, "string", False, None),
    ("unit_price", "decimal:4", "decimal", True, None),
], [])

M("ProcessTemplate", "process_templates", "planning", "Planning", "heroicon-o-map", [
    ("button_family", None, "string", True, None),
    ("name", None, "string", True, None),
], [("hasMany", "stages", "ProcessTemplateStage", "process_template_id")])

M("ProcessTemplateStage", "process_template_stages", "planning", "Planning", "heroicon-o-bars-3", [
    ("process_template_id", None, "belongs", True, ("template", "ProcessTemplate", "name")),
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("sequence", "integer", "int", True, None),
    ("minutes_per_thousand", "decimal:2", "decimal", True, None),
    ("work_center_code", None, "string", False, None),
], [])

M("WorkCenter", "work_centers", "planning", "Planning", "heroicon-o-rectangle-group", [
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("department_id", None, "belongs", False, ("department", "Department", "name")),
    ("hours_per_day", "decimal:2", "decimal", True, None),
], [("hasMany", "machines", "Machine", "work_center_id")])

M("Machine", "machines", "maintenance", "Maintenance", "heroicon-o-cog-6-tooth", [
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("machine_type", None, "string", True, None),
    ("work_center_id", None, "belongs", False, ("workCenter", "WorkCenter", "name")),
    ("department_id", None, "belongs", False, ("department", "Department", "name")),
    ("status", None, "string", True, None),
    ("capacity_per_hour", "decimal:2", "decimal", True, None),
    ("hourly_rate", "decimal:2", "decimal", True, None),
    ("commissioned_on", "date", "date", False, None),
    ("notes", None, "text", False, None),
], [
    ("hasMany", "plans", "MaintenancePlan", "machine_id"),
    ("hasMany", "tickets", "MaintenanceTicket", "machine_id"),
    ("hasMany", "runtimes", "MachineRuntime", "machine_id"),
])

M("MaintenancePlan", "maintenance_plans", "maintenance", "Maintenance", "heroicon-o-wrench-screwdriver", [
    ("machine_id", None, "belongs", True, ("machine", "Machine", "name")),
    ("task", None, "string", True, None),
    ("interval_days", "integer", "int", True, None),
    ("last_done_on", "date", "date", False, None),
    ("next_due_on", "date", "date", True, None),
], [])

M("MaintenanceTicket", "maintenance_tickets", "maintenance", "Maintenance", "heroicon-o-exclamation-triangle", [
    ("number", None, "string", False, None),
    ("machine_id", None, "belongs", True, ("machine", "Machine", "name")),
    ("priority", None, "string", True, None),
    ("status", None, "string", True, None),
    ("title", None, "string", True, None),
    ("description", None, "text", False, None),
    ("suggested_solution", None, "text", False, None),
    ("source", None, "string", False, None),
    ("opened_by", None, "belongs", False, ("opener", "User", "name")),
    ("assigned_to", None, "belongs", False, ("assignee", "User", "name")),
    ("resolved_at", "datetime", "datetime", False, None),
], [])

M("Lot", "lots", "inventory", "Inventory", "heroicon-o-archive-box", [
    ("material_id", None, "belongs", True, ("material", "Material", "name")),
    ("warehouse_id", None, "belongs", True, ("warehouse", "Warehouse", "name")),
    ("bin_id", None, "belongs", False, ("bin", "Bin", "name")),
    ("supplier_id", None, "belongs", False, ("supplier", "Supplier", "name")),
    ("lot_number", None, "string", True, None),
    ("qty_on_hand", "decimal:3", "decimal", True, None),
    ("unit_cost", "decimal:4", "decimal", True, None),
    ("received_at", "datetime", "datetime", False, None),
    ("expires_at", "date", "date", False, None),
    ("status", None, "string", True, None),
], [])

M("StockMovement", "stock_movements", "inventory", "Inventory", "heroicon-o-arrows-right-left", [
    ("material_id", None, "belongs", True, ("material", "Material", "name")),
    ("lot_id", None, "belongs", False, ("lot", "Lot", "lot_number")),
    ("warehouse_id", None, "belongs", True, ("warehouse", "Warehouse", "name")),
    ("type", None, "string", True, None),
    ("qty", "decimal:3", "decimal", True, None),
    ("unit_cost", "decimal:4", "decimal", True, None),
    ("reference_type", None, "string", False, None),
    ("reference_id", "integer", "int", False, None),
    ("notes", None, "text", False, None),
    ("user_id", None, "belongs", False, ("user", "User", "name")),
], [("morphTo", "reference")])

M("ProductStock", "product_stocks", "inventory", "Inventory", "heroicon-o-cube", [
    ("product_id", None, "belongs", True, ("product", "Product", "name")),
    ("warehouse_id", None, "belongs", True, ("warehouse", "Warehouse", "name")),
    ("qty", "decimal:3", "decimal", True, None),
], [])

M("Customer", "customers", "sales", "Sales", "heroicon-o-building-office", [
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("company", None, "string", False, None),
    ("email", None, "string", False, None),
    ("phone", None, "string", False, None),
    ("country", None, "string", False, None),
    ("city", None, "string", False, None),
    ("address", None, "string", False, None),
    ("payment_terms", None, "string", False, None),
], [
    ("hasMany", "inquiries", "Inquiry", "customer_id"),
    ("hasMany", "quotations", "Quotation", "customer_id"),
    ("hasMany", "salesOrders", "SalesOrder", "customer_id"),
    ("hasMany", "invoices", "Invoice", "customer_id"),
])

M("Inquiry", "inquiries", "sales", "Sales", "heroicon-o-inbox", [
    ("customer_id", None, "belongs", False, ("customer", "Customer", "name")),
    ("product_id", None, "belongs", False, ("product", "Product", "name")),
    ("name", None, "string", True, None),
    ("email", None, "string", True, None),
    ("phone", None, "string", False, None),
    ("company", None, "string", False, None),
    ("quantity", "integer", "int", True, None),
    ("message", None, "text", True, None),
    ("locale", None, "string", False, None),
    ("status", None, "string", True, None),
], [])

M("Quotation", "quotations", "sales", "Sales", "heroicon-o-document-text", [
    ("number", None, "string", False, None),
    ("customer_id", None, "belongs", True, ("customer", "Customer", "name")),
    ("inquiry_id", None, "belongs", False, ("inquiry", "Inquiry", "name")),
    ("status", None, "string", True, None),
    ("valid_until", "date", "date", False, None),
    ("currency", None, "string", True, None),
    ("subtotal", "decimal:2", "decimal", False, None),
    ("notes", None, "text", False, None),
], [("hasMany", "lines", "QuotationLine", "quotation_id")])

M("QuotationLine", "quotation_lines", "sales", "Sales", "heroicon-o-minus", [
    ("quotation_id", None, "belongs", True, ("quotation", "Quotation", "number")),
    ("product_id", None, "belongs", True, ("product", "Product", "name")),
    ("qty", "integer", "int", True, None),
    ("unit_price", "decimal:4", "decimal", True, None),
    ("finish", None, "string", False, None),
    ("size_ligne", "integer", "int", False, None),
], [])

M("SalesOrder", "sales_orders", "sales", "Sales", "heroicon-o-shopping-bag", [
    ("number", None, "string", False, None),
    ("customer_id", None, "belongs", True, ("customer", "Customer", "name")),
    ("quotation_id", None, "belongs", False, ("quotation", "Quotation", "number")),
    ("status", None, "string", True, None),
    ("strategy", None, "string", True, None),
    ("ordered_on", "date", "date", True, None),
    ("promised_on", "date", "date", False, None),
    ("currency", None, "string", True, None),
    ("notes", None, "text", False, None),
], [
    ("hasMany", "lines", "SalesOrderLine", "sales_order_id"),
    ("hasMany", "deliveries", "Delivery", "sales_order_id"),
])

M("SalesOrderLine", "sales_order_lines", "sales", "Sales", "heroicon-o-minus", [
    ("sales_order_id", None, "belongs", True, ("salesOrder", "SalesOrder", "number")),
    ("product_id", None, "belongs", True, ("product", "Product", "name")),
    ("qty", "integer", "int", True, None),
    ("qty_delivered", "integer", "int", False, None),
    ("unit_price", "decimal:4", "decimal", True, None),
    ("finish", None, "string", False, None),
], [("hasMany", "workOrders", "WorkOrder", "sales_order_line_id")])

M("WorkOrder", "work_orders", "shopfloor", "Production", "heroicon-o-wrench", [
    ("number", None, "string", False, None),
    ("product_id", None, "belongs", True, ("product", "Product", "name")),
    ("sales_order_line_id", None, "belongs", False, ("salesOrderLine", "SalesOrderLine", "id")),
    ("bom_id", None, "belongs", False, ("bom", "Bom", "id")),
    ("formula_id", None, "belongs", False, ("formula", "Formula", "name")),
    ("parent_work_order_id", None, "belongs", False, ("parent", "WorkOrder", "number")),
    ("is_rework", "boolean", "bool", False, None),
    ("qty_planned", "decimal:3", "decimal", True, None),
    ("qty_completed", "decimal:3", "decimal", False, None),
    ("qty_scrapped", "decimal:3", "decimal", False, None),
    ("status", None, "string", True, None),
    ("strategy", None, "string", True, None),
    ("priority", "integer", "int", False, None),
    ("scheduled_start", "datetime", "datetime", False, None),
    ("scheduled_end", "datetime", "datetime", False, None),
    ("released_at", "datetime", "datetime", False, None),
    ("completed_at", "datetime", "datetime", False, None),
    ("notes", None, "text", False, None),
], [
    ("hasMany", "stages", "WorkOrderStage", "work_order_id"),
    ("hasMany", "issues", "StageIssue", "work_order_id"),
    ("hasOne", "jobCost", "JobCost", "work_order_id"),
    ("hasMany", "children", "WorkOrder", "parent_work_order_id"),
], extra="WORKORDER")

M("WorkOrderStage", "work_order_stages", "shopfloor", "Production", "heroicon-o-forward", [
    ("work_order_id", None, "belongs", True, ("workOrder", "WorkOrder", "number")),
    ("code", None, "string", True, None),
    ("name", None, "string", True, None),
    ("sequence", "integer", "int", True, None),
    ("status", None, "string", True, None),
    ("operator_id", None, "belongs", False, ("operator", "Employee", "name")),
    ("machine_id", None, "belongs", False, ("machine", "Machine", "name")),
    ("started_at", "datetime", "datetime", False, None),
    ("ended_at", "datetime", "datetime", False, None),
    ("expected_minutes", "decimal:2", "decimal", False, None),
    ("qty_in", "decimal:3", "decimal", False, None),
    ("qty_out", "decimal:3", "decimal", False, None),
    ("qty_scrap", "decimal:3", "decimal", False, None),
    ("notes", None, "text", False, None),
], [("hasMany", "issues", "StageIssue", "work_order_stage_id")])

M("MachineRuntime", "machine_runtimes", "maintenance", "Maintenance", "heroicon-o-chart-bar", [
    ("machine_id", None, "belongs", True, ("machine", "Machine", "name")),
    ("work_order_id", None, "belongs", False, ("workOrder", "WorkOrder", "number")),
    ("started_at", "datetime", "datetime", True, None),
    ("ended_at", "datetime", "datetime", False, None),
    ("planned_minutes", "decimal:2", "decimal", False, None),
    ("downtime_minutes", "decimal:2", "decimal", False, None),
    ("good_qty", "decimal:3", "decimal", False, None),
    ("total_qty", "decimal:3", "decimal", False, None),
], [])

M("StageIssue", "stage_issues", "quality", "Quality", "heroicon-o-camera", [
    ("work_order_stage_id", None, "belongs", True, ("stage", "WorkOrderStage", "name")),
    ("work_order_id", None, "belongs", True, ("workOrder", "WorkOrder", "number")),
    ("defect_code", None, "string", True, None),
    ("severity", None, "string", True, None),
    ("description", None, "text", True, None),
    ("photo_path", None, "string", False, None),
    ("status", None, "string", True, None),
    ("ai_analysis", "array", "json", False, None),
    ("suggested_actions", "array", "json", False, None),
    ("reported_by", None, "belongs", False, ("reporter", "User", "name")),
], [])

M("Delivery", "deliveries", "sales", "Sales", "heroicon-o-paper-airplane", [
    ("number", None, "string", False, None),
    ("sales_order_id", None, "belongs", True, ("salesOrder", "SalesOrder", "number")),
    ("status", None, "string", True, None),
    ("shipped_on", "date", "date", False, None),
    ("notes", None, "text", False, None),
], [("hasMany", "lines", "DeliveryLine", "delivery_id")])

M("DeliveryLine", "delivery_lines", "sales", "Sales", "heroicon-o-minus", [
    ("delivery_id", None, "belongs", True, ("delivery", "Delivery", "number")),
    ("sales_order_line_id", None, "belongs", True, ("salesOrderLine", "SalesOrderLine", "id")),
    ("product_id", None, "belongs", True, ("product", "Product", "name")),
    ("qty", "integer", "int", True, None),
], [])

M("PurchaseRequisition", "purchase_requisitions", "procurement", "Procurement", "heroicon-o-clipboard-document-list", [
    ("number", None, "string", False, None),
    ("status", None, "string", True, None),
    ("source", None, "string", True, None),
    ("requested_by", None, "belongs", False, ("requester", "User", "name")),
    ("needed_by", "date", "date", False, None),
    ("notes", None, "text", False, None),
], [("hasMany", "lines", "PurchaseRequisitionLine", "purchase_requisition_id")])

M("PurchaseRequisitionLine", "purchase_requisition_lines", "procurement", "Procurement", "heroicon-o-minus", [
    ("purchase_requisition_id", None, "belongs", True, ("requisition", "PurchaseRequisition", "number")),
    ("material_id", None, "belongs", True, ("material", "Material", "name")),
    ("qty", "decimal:3", "decimal", True, None),
    ("estimated_cost", "decimal:4", "decimal", False, None),
], [])

M("PurchaseOrder", "purchase_orders", "procurement", "Procurement", "heroicon-o-document-currency-dollar", [
    ("number", None, "string", False, None),
    ("supplier_id", None, "belongs", True, ("supplier", "Supplier", "name")),
    ("purchase_requisition_id", None, "belongs", False, ("requisition", "PurchaseRequisition", "number")),
    ("status", None, "string", True, None),
    ("ordered_on", "date", "date", False, None),
    ("expected_on", "date", "date", False, None),
    ("currency", None, "string", True, None),
    ("notes", None, "text", False, None),
], [("hasMany", "lines", "PurchaseOrderLine", "purchase_order_id"), ("hasMany", "receipts", "GoodsReceipt", "purchase_order_id")])

M("PurchaseOrderLine", "purchase_order_lines", "procurement", "Procurement", "heroicon-o-minus", [
    ("purchase_order_id", None, "belongs", True, ("purchaseOrder", "PurchaseOrder", "number")),
    ("material_id", None, "belongs", True, ("material", "Material", "name")),
    ("qty", "decimal:3", "decimal", True, None),
    ("qty_received", "decimal:3", "decimal", False, None),
    ("unit_cost", "decimal:4", "decimal", True, None),
], [])

M("GoodsReceipt", "goods_receipts", "procurement", "Procurement", "heroicon-o-archive-box-arrow-down", [
    ("number", None, "string", False, None),
    ("purchase_order_id", None, "belongs", True, ("purchaseOrder", "PurchaseOrder", "number")),
    ("warehouse_id", None, "belongs", True, ("warehouse", "Warehouse", "name")),
    ("received_on", "date", "date", True, None),
    ("invoice_path", None, "string", False, None),
    ("ocr_payload", "array", "json", False, None),
    ("notes", None, "text", False, None),
], [("hasMany", "lines", "GoodsReceiptLine", "goods_receipt_id")])

M("GoodsReceiptLine", "goods_receipt_lines", "procurement", "Procurement", "heroicon-o-minus", [
    ("goods_receipt_id", None, "belongs", True, ("goodsReceipt", "GoodsReceipt", "number")),
    ("purchase_order_line_id", None, "belongs", False, ("purchaseOrderLine", "PurchaseOrderLine", "id")),
    ("material_id", None, "belongs", True, ("material", "Material", "name")),
    ("lot_id", None, "belongs", False, ("lot", "Lot", "lot_number")),
    ("qty", "decimal:3", "decimal", True, None),
    ("unit_cost", "decimal:4", "decimal", True, None),
], [])

M("Inspection", "inspections", "quality", "Quality", "heroicon-o-check-badge", [
    ("number", None, "string", False, None),
    ("type", None, "string", True, None),
    ("inspectable_type", None, "string", False, None),
    ("inspectable_id", "integer", "int", False, None),
    ("result", None, "string", True, None),
    ("inspector_id", None, "belongs", False, ("inspector", "User", "name")),
    ("sampled_qty", "decimal:3", "decimal", False, None),
    ("rejected_qty", "decimal:3", "decimal", False, None),
    ("notes", None, "text", False, None),
], [("hasMany", "defects", "Defect", "inspection_id"), ("hasOne", "ncr", "Ncr", "inspection_id"), ("morphTo", "inspectable")])

M("Defect", "defects", "quality", "Quality", "heroicon-o-x-circle", [
    ("inspection_id", None, "belongs", True, ("inspection", "Inspection", "number")),
    ("defect_code", None, "string", True, None),
    ("qty", "decimal:3", "decimal", True, None),
    ("photo_path", None, "string", False, None),
    ("notes", None, "text", False, None),
], [])

M("Ncr", "ncrs", "quality", "Quality", "heroicon-o-shield-exclamation", [
    ("number", None, "string", False, None),
    ("inspection_id", None, "belongs", False, ("inspection", "Inspection", "number")),
    ("work_order_id", None, "belongs", False, ("workOrder", "WorkOrder", "number")),
    ("status", None, "string", True, None),
    ("description", None, "text", True, None),
    ("disposition", None, "string", False, None),
    ("opened_by", None, "belongs", False, ("opener", "User", "name")),
], [("hasMany", "capas", "Capa", "ncr_id")])

M("Capa", "capas", "quality", "Quality", "heroicon-o-arrow-path", [
    ("ncr_id", None, "belongs", True, ("ncr", "Ncr", "number")),
    ("action", None, "text", True, None),
    ("owner_id", None, "belongs", False, ("owner", "User", "name")),
    ("due_on", "date", "date", False, None),
    ("status", None, "string", True, None),
    ("effectiveness_notes", None, "text", False, None),
], [])

M("SpcSample", "spc_samples", "quality", "Quality", "heroicon-o-presentation-chart-line", [
    ("work_order_stage_id", None, "belongs", False, ("stage", "WorkOrderStage", "name")),
    ("characteristic", None, "string", True, None),
    ("value", "decimal:4", "decimal", True, None),
    ("sampled_at", "datetime", "datetime", True, None),
], [])

M("Invoice", "invoices", "finance", "Finance", "heroicon-o-receipt-percent", [
    ("number", None, "string", False, None),
    ("customer_id", None, "belongs", True, ("customer", "Customer", "name")),
    ("sales_order_id", None, "belongs", False, ("salesOrder", "SalesOrder", "number")),
    ("status", None, "string", True, None),
    ("issued_on", "date", "date", True, None),
    ("due_on", "date", "date", False, None),
    ("currency", None, "string", True, None),
    ("subtotal", "decimal:2", "decimal", False, None),
    ("tax", "decimal:2", "decimal", False, None),
    ("total", "decimal:2", "decimal", False, None),
    ("notes", None, "text", False, None),
], [("hasMany", "lines", "InvoiceLine", "invoice_id"), ("morphMany", "payments", "Payment", "payable")])

M("InvoiceLine", "invoice_lines", "finance", "Finance", "heroicon-o-minus", [
    ("invoice_id", None, "belongs", True, ("invoice", "Invoice", "number")),
    ("description", None, "string", True, None),
    ("qty", "decimal:3", "decimal", True, None),
    ("unit_price", "decimal:4", "decimal", True, None),
], [])

M("Payment", "payments", "finance", "Finance", "heroicon-o-banknotes", [
    ("payable_type", None, "string", True, None),
    ("payable_id", "integer", "int", True, None),
    ("direction", None, "string", True, None),
    ("amount", "decimal:2", "decimal", True, None),
    ("paid_on", "date", "date", True, None),
    ("method", None, "string", True, None),
    ("reference", None, "string", False, None),
], [("morphTo", "payable")])

M("JobCost", "job_costs", "finance", "Finance", "heroicon-o-calculator", [
    ("work_order_id", None, "belongs", True, ("workOrder", "WorkOrder", "number")),
    ("material_cost", "decimal:2", "decimal", False, None),
    ("labor_cost", "decimal:2", "decimal", False, None),
    ("machine_cost", "decimal:2", "decimal", False, None),
    ("overhead_cost", "decimal:2", "decimal", False, None),
    ("total_cost", "decimal:2", "decimal", False, None),
    ("unit_cost", "decimal:4", "decimal", False, None),
    ("calculated_at", "datetime", "datetime", False, None),
], [])

M("AutomationLog", "automation_logs", "system", "System", "heroicon-o-cpu-chip", [
    ("trigger", None, "string", True, None),
    ("rule", None, "string", True, None),
    ("subject_type", None, "string", False, None),
    ("subject_id", "integer", "int", False, None),
    ("action", None, "string", True, None),
    ("payload", "array", "json", False, None),
], [("morphTo", "subject")])

M("AuditLog", "audit_logs", "system", "System", "heroicon-o-clipboard-document-check", [
    ("user_id", None, "belongs", False, ("user", "User", "name")),
    ("auditable_type", None, "string", False, None),
    ("auditable_id", "integer", "int", False, None),
    ("event", None, "string", True, None),
    ("old_values", "array", "json", False, None),
    ("new_values", "array", "json", False, None),
    ("ip", None, "string", False, None),
], [("morphTo", "auditable")])


EXTRAS = {
"SLUG": '''
    public function getSlugOptions(): array
    {
        return [
            SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->usingLanguage('en')->usingMode('ascii_only')->useAsRouteKey(false),
            SlugOptions::create()->generateSlugsFrom(fn () => $this->name_bn ?: $this->name)->saveSlugsTo('slug_bn')->usingLanguage('bn')->usingMode('preserve_unicode')->useAsRouteKey(false),
        ];
    }
''',
"PRODUCT": '''
    public function getSlugOptions(): array
    {
        return [
            SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->usingLanguage('en')->usingMode('ascii_only')->useAsRouteKey(false),
            SlugOptions::create()->generateSlugsFrom(fn () => $this->name_bn ?: $this->name)->saveSlugsTo('slug_bn')->usingLanguage('bn')->usingMode('preserve_unicode')->useAsRouteKey(false),
        ];
    }

    public function priceFor(int $qty, ?string $finish = null): string
    {
        $break = $this->priceBreaks()
            ->where('min_qty', '<=', $qty)
            ->when($finish, function ($query) use ($finish) {
                $query->where(function ($inner) use ($finish) {
                    $inner->whereNull('finish')->orWhere('finish', $finish);
                });
            })
            ->orderByDesc('min_qty')
            ->first();

        return (string) ($break->unit_price ?? $this->list_price);
    }

    public function onHand(): float
    {
        return (float) $this->stocks()->sum('qty');
    }

    public function activeBom(): ?Bom
    {
        return $this->boms()->where('status', 'active')->orderByDesc('version')->first();
    }

    public function activeFormula(): ?Formula
    {
        return $this->formulas()->where('status', 'active')->orderByDesc('version')->first();
    }

    /** @return array<string, mixed> */
    public function jsonLdPayload(): array
    {
        if (is_array($this->json_ld) && $this->json_ld !== []) {
            return $this->json_ld;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->excerpt ?: $this->description,
            'material' => $this->material_label,
            'color' => $this->color,
            'brand' => ['@type' => 'Brand', 'name' => config('factory.name')],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => config('factory.currency'),
                'price' => (string) $this->list_price,
                'availability' => 'https://schema.org/PreOrder',
            ],
        ];
    }
''',
"MATERIAL": '''
    public function onHand(): float
    {
        return (float) $this->lots()->sum('qty_on_hand');
    }

    public function needsReorder(): bool
    {
        return $this->is_active && $this->onHand() <= (float) $this->min_qty;
    }
''',
"WORKORDER": '''
    public function scrapRate(): float
    {
        $planned = (float) $this->qty_planned;

        return $planned > 0 ? (float) $this->qty_scrapped / $planned : 0.0;
    }
''',
}

def php_string(value: str) -> str:
    return "'" + value.replace("\\", "\\\\").replace("'", "\\'") + "'"

def write(path: Path, content: str) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(content.strip() + "\n")

def render_model(spec) -> str:
    name = spec["name"]
    uses = ["use Illuminate\\Database\\Eloquent\\Model;", "use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;", "use Illuminate\\Database\\Eloquent\\Relations\\HasMany;", "use Illuminate\\Database\\Eloquent\\Relations\\HasOne;", "use Illuminate\\Database\\Eloquent\\Relations\\MorphMany;", "use Illuminate\\Database\\Eloquent\\Relations\\MorphTo;", "use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;"]
    traits = ["HasFactory"]
    if name in AUDITED:
        uses.append("use App\\Models\\Concerns\\Auditable;")
        traits.append("Auditable")
    if name in NUMBERED:
        uses.append("use App\\Models\\Concerns\\HasDocumentNumber;")
        traits.append("HasDocumentNumber")
    if spec["extra"] in ("SLUG", "PRODUCT"):
        traits.append("HasUniversalSlug")
        uses.append("use ImranDev\\UniversalSlug\\SlugOptions;")
        uses.append("use ImranDev\\UniversalSlug\\Traits\\HasUniversalSlug;")
    fillable = ",\n        ".join(php_string(f[0]) for f in spec["fields"])
    casts = []
    for field, cast, *_ in spec["fields"]:
        if cast:
            casts.append(f"{php_string(field)} => {php_string(cast)}")
    cast_body = ",\n            ".join(casts)
    cast_block = f"\n            {cast_body},\n        " if cast_body else ""
    methods = []
    for rel in spec["rels"]:
        kind = rel[0]
        meth = rel[1]
        if kind == "morphTo":
            methods.append(f"    public function {meth}(): MorphTo\n    {{\n        return $this->morphTo();\n    }}")
            continue
        related, fk = rel[2], rel[3]
        if kind == "hasMany":
            methods.append(f"    public function {meth}(): HasMany\n    {{\n        return $this->hasMany({related}::class, {php_string(fk)});\n    }}")
        elif kind == "hasOne":
            methods.append(f"    public function {meth}(): HasOne\n    {{\n        return $this->hasOne({related}::class, {php_string(fk)});\n    }}")
        elif kind == "morphMany":
            methods.append(f"    public function {meth}(): MorphMany\n    {{\n        return $this->morphMany({related}::class, {php_string(fk)});\n    }}")
    for field in spec["fields"]:
        if field[2] == "belongs":
            meth, related, title = field[4]
            methods.append(f"    public function {meth}(): BelongsTo\n    {{\n        return $this->belongsTo({related}::class, {php_string(field[0])});\n    }}")
    prefix = ""
    if name in NUMBERED:
        prefix = f"\n    public function documentPrefix(): string\n    {{\n        return {php_string(NUMBERED[name])};\n    }}\n"
    extra = EXTRAS.get(spec["extra"], "")
    trait_use = "use " + ", ".join(traits) + ";"
    return f"""<?php

namespace App\\Models;

{chr(10).join(uses)}

class {name} extends Model
{{
    {trait_use}

    protected $table = {php_string(spec['table'])};

    protected $fillable = [
        {fillable},
    ];

    protected function casts(): array
    {{
        return [{cast_block}];
    }}
{prefix}
{extra}
{chr(10)+chr(10).join(methods)}
}}
"""

def form_field(field) -> str:
    col, cast, kind, required, rel = field
    req = "->required()" if required else ""
    if kind == "belongs":
        meth, related, title = rel
        return f"Select::make({php_string(col)})->label({php_string(meth.replace('_',' ').title())})->relationship({php_string(meth)}, {php_string(title)})->searchable()->preload(){req}"
    if kind == "bool":
        return f"Toggle::make({php_string(col)})"
    if kind == "text":
        return f"Textarea::make({php_string(col)})->columnSpanFull(){req}"
    if kind == "json":
        return f"KeyValue::make({php_string(col)})->columnSpanFull()"
    if kind == "tags":
        return f"TagsInput::make({php_string(col)})"
    if kind == "int":
        return f"TextInput::make({php_string(col)})->numeric(){req}"
    if kind == "decimal":
        return f"TextInput::make({php_string(col)})->numeric(){req}"
    if kind == "date":
        return f"DatePicker::make({php_string(col)}){req}"
    if kind == "datetime":
        return f"DateTimePicker::make({php_string(col)}){req}"
    return f"TextInput::make({php_string(col)}){req}->maxLength(255)"

def table_cols(spec) -> str:
    cols = []
    for field in spec["fields"][:6]:
        col = field[0]
        if field[2] == "belongs":
            cols.append(f"TextColumn::make({php_string(field[4][0]+'.'+field[4][2])})->label({php_string(field[4][0])})")
        elif field[2] == "bool":
            cols.append(f"IconColumn::make({php_string(col)})->boolean()")
        else:
            extra = "->searchable()->sortable()" if field[2] in ("string", "int") else ""
            cols.append(f"TextColumn::make({php_string(col)}){extra}")
    return ",\n                ".join(cols)

def render_resource(spec) -> str:
    name = spec["name"]
    fields = ",\n                ".join(form_field(f) for f in spec["fields"])
    rich = name in RICH
    relations = ""
    if name in RELATIONS:
        classes = ",\n            ".join(f"RelationManagers\\{item[1]}::class" for item in RELATIONS[name])
        relations = f"""
    public static function getRelations(): array
    {{
        return [
            {classes},
        ];
    }}
"""
    if rich:
        pages = f"""'index' => Pages\\List{name}s::route('/'),
            'create' => Pages\\Create{name}::route('/create'),
            'edit' => Pages\\Edit{name}::route('/{{record}}/edit'),"""
    else:
        pages = f"""'index' => Pages\\Manage{name}s::route('/'),"""
    return f"""<?php

namespace App\\Filament\\Resources;

use App\\Filament\\Resources\\{name}Resource\\Pages;
use App\\Filament\\Resources\\{name}Resource\\RelationManagers;
use App\\Models\\{name};
use Filament\\Actions\\BulkActionGroup;
use Filament\\Actions\\DeleteAction;
use Filament\\Actions\\DeleteBulkAction;
use Filament\\Actions\\EditAction;
use Filament\\Forms\\Components\\DatePicker;
use Filament\\Forms\\Components\\DateTimePicker;
use Filament\\Forms\\Components\\KeyValue;
use Filament\\Forms\\Components\\Select;
use Filament\\Forms\\Components\\TagsInput;
use Filament\\Forms\\Components\\Textarea;
use Filament\\Forms\\Components\\TextInput;
use Filament\\Forms\\Components\\Toggle;
use Filament\\Resources\\Resource;
use Filament\\Schemas\\Schema;
use Filament\\Tables\\Columns\\IconColumn;
use Filament\\Tables\\Columns\\TextColumn;
use Filament\\Tables\\Table;

class {name}Resource extends Resource
{{
    protected static ?string $model = {name}::class;

    protected static string|\\BackedEnum|null $navigationIcon = {php_string(spec['icon'])};

    protected static string|\\UnitEnum|null $navigationGroup = {php_string(spec['group'])};

    protected static ?string $recordTitleAttribute = {php_string('name' if any(f[0]=='name' for f in spec['fields']) else ('number' if any(f[0]=='number' for f in spec['fields']) else spec['fields'][0][0]))};

    public static function canViewAny(): bool
    {{
        return (bool) config('factory.modules.{spec['module']}') && parent::canViewAny();
    }}

    public static function form(Schema $schema): Schema
    {{
        return $schema->columns(2)->components([
                {fields},
        ]);
    }}

    public static function table(Table $table): Table
    {{
        return $table
            ->columns([
                {table_cols(spec)},
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }}
{relations}
    public static function getPages(): array
    {{
        return [
            {pages}
        ];
    }}
}}
"""

def render_manage(name) -> str:
    return f"""<?php

namespace App\\Filament\\Resources\\{name}Resource\\Pages;

use App\\Filament\\Resources\\{name}Resource;
use Filament\\Actions\\CreateAction;
use Filament\\Resources\\Pages\\ManageRecords;

class Manage{name}s extends ManageRecords
{{
    protected static string $resource = {name}Resource::class;

    protected function getHeaderActions(): array
    {{
        return [CreateAction::make()];
    }}
}}
"""

def render_list(name) -> str:
    return f"""<?php

namespace App\\Filament\\Resources\\{name}Resource\\Pages;

use App\\Filament\\Resources\\{name}Resource;
use Filament\\Actions\\CreateAction;
use Filament\\Resources\\Pages\\ListRecords;

class List{name}s extends ListRecords
{{
    protected static string $resource = {name}Resource::class;

    protected function getHeaderActions(): array
    {{
        return [CreateAction::make()];
    }}
}}
"""

def render_create(name) -> str:
    return f"""<?php

namespace App\\Filament\\Resources\\{name}Resource\\Pages;

use App\\Filament\\Resources\\{name}Resource;
use Filament\\Resources\\Pages\\CreateRecord;

class Create{name} extends CreateRecord
{{
    protected static string $resource = {name}Resource::class;
}}
"""

def render_edit(name) -> str:
    return f"""<?php

namespace App\\Filament\\Resources\\{name}Resource\\Pages;

use App\\Filament\\Resources\\{name}Resource;
use Filament\\Actions\\DeleteAction;
use Filament\\Resources\\Pages\\EditRecord;

class Edit{name} extends EditRecord
{{
    protected static string $resource = {name}Resource::class;

    protected function getHeaderActions(): array
    {{
        return [DeleteAction::make()];
    }}
}}
"""

def render_relation(parent, meth, class_name, child_name) -> str:
    child = next(m for m in MODELS if m["name"] == child_name)
    # fields except the parent fk
    parent_fks = {f[0] for f in child["fields"] if f[2] == "belongs" and f[4][0] == meth.rstrip("s") or False}
    fields = []
    for f in child["fields"]:
        if f[2] == "belongs" and f[4][1] == parent:
            continue
        fields.append(form_field(f))
    field_php = ",\n                ".join(fields) if fields else "TextInput::make('id')->disabled()"
    cols = table_cols(child)
    return f"""<?php

namespace App\\Filament\\Resources\\{parent}Resource\\RelationManagers;

use Filament\\Actions\\BulkActionGroup;
use Filament\\Actions\\CreateAction;
use Filament\\Actions\\DeleteAction;
use Filament\\Actions\\DeleteBulkAction;
use Filament\\Actions\\EditAction;
use Filament\\Forms\\Components\\DatePicker;
use Filament\\Forms\\Components\\DateTimePicker;
use Filament\\Forms\\Components\\KeyValue;
use Filament\\Forms\\Components\\Select;
use Filament\\Forms\\Components\\TagsInput;
use Filament\\Forms\\Components\\Textarea;
use Filament\\Forms\\Components\\TextInput;
use Filament\\Forms\\Components\\Toggle;
use Filament\\Resources\\RelationManagers\\RelationManager;
use Filament\\Schemas\\Schema;
use Filament\\Tables\\Columns\\IconColumn;
use Filament\\Tables\\Columns\\TextColumn;
use Filament\\Tables\\Table;

class {class_name} extends RelationManager
{{
    protected static string $relationship = {php_string(meth)};

    public function form(Schema $schema): Schema
    {{
        return $schema->columns(2)->components([
                {field_php},
        ]);
    }}

    public function table(Table $table): Table
    {{
        return $table
            ->columns([
                {cols},
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }}
}}
"""

def render_policy(spec) -> str:
    return f"""<?php

namespace App\\Policies;

use App\\Models\\{spec['name']};
use App\\Models\\User;

class {spec['name']}Policy extends ModulePolicy
{{
    protected function module(): string
    {{
        return {php_string(spec['module'])};
    }}
}}
"""

def render_factory(spec) -> str:
    name = spec["name"]
    defs = []
    for col, cast, kind, required, rel in spec["fields"]:
        if not required:
            continue
        if kind == "belongs":
            defs.append(f"'{col}' => \\App\\Models\\{rel[1]}::factory()")
        elif kind == "bool":
            defs.append(f"'{col}' => true")
        elif kind == "int":
            defs.append(f"'{col}' => fake()->numberBetween(1, 20)")
        elif kind == "decimal":
            defs.append(f"'{col}' => fake()->randomFloat(2, 1, 100)")
        elif kind == "date":
            defs.append(f"'{col}' => now()->toDateString()")
        elif kind == "datetime":
            defs.append(f"'{col}' => now()")
        elif kind == "text":
            defs.append(f"'{col}' => fake()->sentence()")
        elif col in ("slug", "slug_bn", "number"):
            continue
        else:
            defs.append(f"'{col}' => fake()->unique()->bothify('{name[:3].upper()}-####')")
    body = ",\n            ".join(defs) if defs else "'id' => null"
    # factories that recurse explode. Use null for belongs and let tests set them.
    defs = []
    for col, cast, kind, required, rel in spec["fields"]:
        if col in ("slug", "slug_bn", "number"):
            continue
        if kind == "belongs":
            continue
        if not required and kind != "bool":
            continue
        if kind == "bool":
            defs.append(f"'{col}' => true")
        elif kind == "int":
            defs.append(f"'{col}' => 10")
        elif kind == "decimal":
            defs.append(f"'{col}' => 10")
        elif kind == "date":
            defs.append(f"'{col}' => now()->toDateString()")
        elif kind == "datetime":
            defs.append(f"'{col}' => now()")
        elif kind == "text":
            defs.append(f"'{col}' => 'Factory note'")
        else:
            defs.append(f"'{col}' => fake()->unique()->lexify('{name[:2].lower()}????')")
    body = (",\n            ".join(defs) + ",") if defs else ""
    return f"""<?php

namespace Database\\Factories;

use App\\Models\\{name};
use Illuminate\\Database\\Eloquent\\Factories\\Factory;

/** @extends Factory<{name}> */
class {name}Factory extends Factory
{{
    protected $model = {name}::class;

    public function definition(): array
    {{
        return [
            {body}
        ];
    }}
}}
"""

def main() -> None:
    for spec in MODELS:
        write(ROOT / f"app/Models/{spec['name']}.php", render_model(spec))
        write(ROOT / f"app/Policies/{spec['name']}Policy.php", render_policy(spec))
        write(ROOT / f"database/factories/{spec['name']}Factory.php", render_factory(spec))
        write(ROOT / f"app/Filament/Resources/{spec['name']}Resource.php", render_resource(spec))
        if spec["name"] in RICH:
            write(ROOT / f"app/Filament/Resources/{spec['name']}Resource/Pages/List{spec['name']}s.php", render_list(spec["name"]))
            write(ROOT / f"app/Filament/Resources/{spec['name']}Resource/Pages/Create{spec['name']}.php", render_create(spec["name"]))
            write(ROOT / f"app/Filament/Resources/{spec['name']}Resource/Pages/Edit{spec['name']}.php", render_edit(spec["name"]))
        else:
            write(ROOT / f"app/Filament/Resources/{spec['name']}Resource/Pages/Manage{spec['name']}s.php", render_manage(spec["name"]))
        for meth, class_name, child in RELATIONS.get(spec["name"], []):
            child_model = {
                "components": "FormulaComponent",
                "items": "BomItem",
                "lines": {
                    "Quotation": "QuotationLine",
                    "SalesOrder": "SalesOrderLine",
                    "PurchaseOrder": "PurchaseOrderLine",
                    "GoodsReceipt": "GoodsReceiptLine",
                    "Invoice": "InvoiceLine",
                }.get(spec["name"]),
                "stages": "WorkOrderStage",
                "defects": "Defect",
                "capas": "Capa",
            }[meth]
            write(
                ROOT / f"app/Filament/Resources/{spec['name']}Resource/RelationManagers/{class_name}.php",
                render_relation(spec["name"], meth, class_name, child_model),
            )
    print(f"generated {len(MODELS)} models")

if __name__ == "__main__":
    main()
