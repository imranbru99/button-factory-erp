<x-filament-panels::page>
    @php
        $orders = \App\Models\WorkOrder::query()
            ->with(['product', 'stages.operator', 'stages.machine'])
            ->whereIn('status', ['released', 'in_progress', 'qc_hold', 'planned'])
            ->latest('id')
            ->limit(12)
            ->get();
        $current = $orders->firstWhere('id', $this->workOrderId) ?? $orders->first();
        $defects = config('factory.defect_codes');
    @endphp

    <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
        <div class="space-y-2">
            @forelse ($orders as $order)
                <button type="button" wire:click="$set('workOrderId', {{ $order->id }})"
                    class="w-full rounded-xl border px-3 py-2 text-left {{ $current && $current->id === $order->id ? 'border-amber-500 bg-amber-50 dark:bg-amber-500/10' : 'border-gray-200 dark:border-gray-700' }}">
                    <div class="text-sm font-semibold">{{ $order->number }}</div>
                    <div class="text-xs text-gray-500">{{ $order->product->name }} · {{ $order->status }}</div>
                </button>
            @empty
                <p class="text-sm text-gray-500">No open work orders. Confirm a sales order and run MRP.</p>
            @endforelse
        </div>

        @if ($current)
            <div class="space-y-4">
                <div class="flex flex-wrap items-end gap-3">
                    <label class="text-sm">Operator
                        <select wire:model="operatorId" class="mt-1 block rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900">
                            <option value="">Unassigned</option>
                            @foreach (\App\Models\Employee::query()->where('is_active', true)->orderBy('name')->get() as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm">Machine
                        <select wire:model="machineId" class="mt-1 block rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900">
                            <option value="">Unassigned</option>
                            @foreach (\App\Models\Machine::query()->orderBy('code')->get() as $machine)
                                <option value="{{ $machine->id }}">{{ $machine->code }} · {{ $machine->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="text-sm text-gray-500">Planned {{ $current->qty_planned }} · done {{ $current->qty_completed }} · scrap {{ $current->qty_scrapped }}</div>
                </div>

                <ol class="space-y-3">
                    @php $previousDone = true; @endphp
                    @foreach ($current->stages as $stage)
                        <li class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-medium">{{ $stage->sequence }}. {{ $stage->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $stage->status }} · in {{ $stage->qty_in }} · out {{ $stage->qty_out }} · scrap {{ $stage->qty_scrap }}
                                        @if ($stage->started_at) · started {{ $stage->started_at->format('H:i') }} @endif
                                    </div>
                                </div>
                                @if (($stage->status === 'pending' || $stage->status === 'paused') && $previousDone)
                                    <x-filament::button size="sm" wire:click="startStage({{ $stage->id }})">Start</x-filament::button>
                                @endif
                            </div>
                            @if ($stage->status === 'running')
                                <div class="mt-3 flex flex-wrap items-end gap-2">
                                    <label class="text-xs">Good qty <input type="number" wire:model="qtyOut" class="block w-28 rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900"></label>
                                    <label class="text-xs">Scrap <input type="number" wire:model="qtyScrap" class="block w-28 rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900"></label>
                                    <x-filament::button size="sm" color="success" wire:click="completeStage({{ $stage->id }})">Complete</x-filament::button>
                                </div>
                                <div class="mt-3 grid gap-2 md:grid-cols-4">
                                    <select wire:model="defectCode" class="rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900">
                                        @foreach ($defects as $code => $label)
                                            <option value="{{ $code }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input wire:model="issueDescription" placeholder="What went wrong?" class="rounded-lg border-gray-300 text-sm md:col-span-2 dark:border-gray-700 dark:bg-gray-900">
                                    <input type="file" wire:model="photo" accept="image/*" class="text-xs">
                                    <x-filament::button size="sm" color="danger" wire:click="reportIssue({{ $stage->id }})">Report issue</x-filament::button>
                                </div>
                            @endif
                        </li>
                        @php $previousDone = $stage->status === 'completed'; @endphp
                    @endforeach
                </ol>
            </div>
        @endif
    </div>
</x-filament-panels::page>
