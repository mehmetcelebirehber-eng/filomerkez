<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Fleet\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('expenses.view'), 403);

        $companyId = auth()->user()->company_id;
        $vehicles = Vehicle::where('company_id', $companyId)->orderBy('plate')->get();

        $query = Expense::with('vehicle')->where('company_id', $companyId);

        // Filtrelemeler
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

        $expenses = $query->orderByDesc('date')->orderByDesc('id')->get();

        // İstatistikler (Bu Ay)
        $thisMonthExpenses = Expense::where('company_id', $companyId)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get();

        $totalThisMonth = $thisMonthExpenses->sum('amount');

        $topVehicleId = $thisMonthExpenses->groupBy('vehicle_id')
            ->map(function ($row) {
                return $row->sum('amount');
            })
            ->sortDesc()
            ->keys()
            ->first();

        $topVehicle = $topVehicleId ? Vehicle::find($topVehicleId) : null;

        $typeDistribution = $thisMonthExpenses->groupBy('type')
            ->map(function ($row) {
                return $row->sum('amount');
            })
            ->sortDesc();

        return view('expenses.index', compact(
            'expenses', 
            'vehicles', 
            'totalThisMonth', 
            'topVehicle', 
            'typeDistribution'
        ));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('expenses.view'), 403);

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['created_by'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla eklendi.');
    }

    public function update(Request $request, Expense $expense)
    {
        abort_unless(auth()->user()->hasPermission('expenses.view'), 403);
        abort_unless($expense->company_id === auth()->user()->company_id, 404);

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla güncellendi.');
    }

    public function destroy(Expense $expense)
    {
        abort_unless(auth()->user()->hasPermission('expenses.view'), 403);
        abort_unless($expense->company_id === auth()->user()->company_id, 404);

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla silindi.');
    }
}
