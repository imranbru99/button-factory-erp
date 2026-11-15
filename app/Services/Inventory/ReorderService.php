<?php

namespace App\Services\Inventory;

use App\Models\Material;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionLine;
use App\Models\User;
use App\Notifications\LowStockNotification;
use App\Services\Automation\AutomationLogger;

class ReorderService
{
    public function check(Material $material): ?PurchaseRequisition
    {
        if ($material->is_active === false || ! $material->needsReorder()) {
            return null;
        }

        $open = PurchaseRequisitionLine::query()
            ->where('material_id', $material->id)
            ->whereHas('requisition', fn ($query) => $query->whereIn('status', ['open', 'approved']))
            ->exists();

        if ($open) {
            return null;
        }

        $requisition = PurchaseRequisition::query()->create([
            'status' => 'open',
            'source' => 'reorder',
            'needed_by' => now()->addDays(7)->toDateString(),
            'notes' => "Auto purchase request. {$material->name} is at or below minimum.",
        ]);

        $qty = max((float) $material->reorder_qty, (float) $material->min_qty - $material->onHand());
        PurchaseRequisitionLine::query()->create([
            'purchase_requisition_id' => $requisition->id,
            'material_id' => $material->id,
            'qty' => $qty,
            'estimated_cost' => $qty * (float) $material->standard_cost,
        ]);

        AutomationLogger::record('stock', 'min_max_reorder', $material, 'purchase_requisition', [
            'requisition' => $requisition->number,
            'qty' => $qty,
        ]);

        User::query()->whereIn('role', ['super_admin', 'production_manager', 'accountant'])->get()
            ->each(fn (User $user) => $user->notify(new LowStockNotification($material, $requisition)));

        return $requisition;
    }

    public function scan(): int
    {
        $created = 0;
        Material::query()->where('is_active', true)->each(function (Material $material) use (&$created) {
            if ($this->check($material)) {
                $created++;
            }
        });

        return $created;
    }
}
