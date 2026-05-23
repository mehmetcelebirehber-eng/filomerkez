<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpenseApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!auth()->user()->hasPermission('expenses.view')) {
            return response()->json(['message' => 'Yetkiniz yok.'], 403);
        }

        $companyId = auth()->user()->company_id;
        $query = Expense::with('vehicle:id,plate')->where('company_id', $companyId);

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_start')) {
            $query->whereDate('date', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('date', '<=', $request->date_end);
        }

        $expenses = $query->orderByDesc('date')->orderByDesc('id')->get()->map(function($expense) {
            return [
                'id' => $expense->id,
                'vehicle_id' => $expense->vehicle_id,
                'vehicle_plate' => $expense->vehicle ? $expense->vehicle->plate : null,
                'type' => $expense->type,
                'type_name' => $expense->type_name,
                'amount' => $expense->amount,
                'date' => $expense->date->format('Y-m-d'),
                'description' => $expense->description,
            ];
        });

        return response()->json([
            'expenses' => $expenses,
            'types' => Expense::getTypes(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!auth()->user()->hasPermission('expenses.view')) {
            return response()->json(['message' => 'Yetkiniz yok.'], 403);
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['created_by'] = auth()->id();

        $expense = Expense::create($validated);

        return response()->json([
            'message' => 'Gider başarıyla eklendi.',
            'expense' => $expense
        ], 201);
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        if (!auth()->user()->hasPermission('expenses.view')) {
            return response()->json(['message' => 'Yetkiniz yok.'], 403);
        }

        if ($expense->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Bulunamadı.'], 404);
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense->update($validated);

        return response()->json([
            'message' => 'Gider başarıyla güncellendi.',
            'expense' => $expense
        ]);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        if (!auth()->user()->hasPermission('expenses.view')) {
            return response()->json(['message' => 'Yetkiniz yok.'], 403);
        }

        if ($expense->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Bulunamadı.'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Gider silindi.']);
    }
}
