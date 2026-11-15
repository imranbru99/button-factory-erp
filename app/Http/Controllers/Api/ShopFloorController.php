<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Machine;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderStage;
use App\Services\Automation\IssueAutomationEngine;
use App\Services\Production\ProductionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ShopFloorController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $data['email'])->where('is_active', true)->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'These credentials are not valid.']);
        }

        return response()->json([
            'token' => $user->createToken('shop-floor')->plainTextToken,
            'user' => ['name' => $user->name, 'role' => $user->role],
        ]);
    }

    public function orders(): JsonResponse
    {
        $orders = WorkOrder::query()
            ->with(['product:id,name,sku,size_ligne', 'stages:id,work_order_id,name,code,sequence,status,qty_in,qty_out,qty_scrap'])
            ->whereIn('status', ['released', 'in_progress', 'qc_hold', 'planned'])
            ->latest('id')
            ->limit(50)
            ->get();

        return response()->json(['data' => $orders]);
    }

    public function start(Request $request, WorkOrderStage $stage, ProductionService $production): JsonResponse
    {
        $stage = $production->start(
            $stage,
            Employee::query()->find($request->integer('operator_id')),
            Machine::query()->find($request->integer('machine_id')),
        );

        return response()->json(['data' => $stage]);
    }

    public function complete(Request $request, WorkOrderStage $stage, ProductionService $production): JsonResponse
    {
        $data = $request->validate([
            'qty_out' => ['required', 'numeric', 'min:0'],
            'qty_scrap' => ['required', 'numeric', 'min:0'],
        ]);

        $stage = $production->complete($stage, (float) $data['qty_out'], (float) $data['qty_scrap']);

        return response()->json(['data' => $stage->load('workOrder')]);
    }

    public function issue(Request $request, WorkOrderStage $stage, IssueAutomationEngine $engine): JsonResponse
    {
        $data = $request->validate([
            'defect_code' => ['required', 'string'],
            'description' => ['required', 'string'],
            'severity' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $path = $request->file('photo')?->store('issues', 'public');
        $issue = $engine->report($stage, $data['defect_code'], $data['description'], $path, $data['severity'] ?? 'medium');

        return response()->json(['data' => $issue], 201);
    }
}
