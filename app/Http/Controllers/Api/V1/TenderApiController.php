<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenderApiController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('tenders.view')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $query = Tender::orderBy('tender_date', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('institution_name', 'like', "%{$search}%")
                  ->orWhere('tender_registration_number', 'like', "%{$search}%")
                  ->orWhere('winning_company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('year')) {
            $query->whereYear('tender_date', $request->year);
        }

        $tenders = $query->get()->map(function ($tender) {
            $tender->file_url = $tender->document_path ? url('storage/' . $tender->document_path) : null;
            return $tender;
        });

        // Summary Statistics
        $total = $tenders->count();
        $won = $tenders->where('status', 'Kazanıldı')->count();
        $lost = $tenders->where('status', 'Kaybedildi')->count();
        $evaluating = $tenders->where('status', 'Değerlendirmede')->count();

        return response()->json([
            'success' => true,
            'data' => $tenders,
            'stats' => [
                'total' => $total,
                'won' => $won,
                'lost' => $lost,
                'evaluating' => $evaluating
            ]
        ]);
    }

    public function show($id)
    {
        if (!auth()->user()->hasPermission('tenders.view')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($id);
        $tender->file_url = $tender->document_path ? url('storage/' . $tender->document_path) : null;

        return response()->json(['success' => true, 'data' => $tender]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('tenders.create')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'tender_date' => 'required|date',
            'tender_registration_number' => 'nullable|string|max:255',
            'vehicle_details' => 'nullable|string',
            'duration_days' => 'nullable|integer',
            'approximate_cost' => 'nullable|numeric',
            'our_bid' => 'nullable|numeric',
            'winning_company' => 'nullable|string|max:255',
            'winning_amount' => 'nullable|numeric',
            'status' => 'required|in:Değerlendirmede,Kazanıldı,Kaybedildi,İptal',
            'document' => 'nullable|file|max:20480',
            'notes' => 'nullable|string'
        ]);

        if ($request->hasFile('document')) {
            $validated['document_path'] = $request->file('document')->store('tenders', 'public');
        }

        $validated['company_id'] = auth()->user()->company_id;

        $tender = Tender::create($validated);
        $tender->file_url = $tender->document_path ? url('storage/' . $tender->document_path) : null;

        return response()->json(['success' => true, 'message' => 'İhale kaydedildi.', 'data' => $tender]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('tenders.edit')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($id);

        $validated = $request->validate([
            'institution_name' => 'sometimes|required|string|max:255',
            'tender_date' => 'sometimes|required|date',
            'tender_registration_number' => 'nullable|string|max:255',
            'vehicle_details' => 'nullable|string',
            'duration_days' => 'nullable|integer',
            'approximate_cost' => 'nullable|numeric',
            'our_bid' => 'nullable|numeric',
            'winning_company' => 'nullable|string|max:255',
            'winning_amount' => 'nullable|numeric',
            'status' => 'sometimes|required|in:Değerlendirmede,Kazanıldı,Kaybedildi,İptal',
            'document' => 'nullable|file|max:20480',
            'notes' => 'nullable|string'
        ]);

        if ($request->hasFile('document')) {
            if ($tender->document_path && Storage::disk('public')->exists($tender->document_path)) {
                Storage::disk('public')->delete($tender->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('tenders', 'public');
        }

        $tender->update($validated);
        $tender->file_url = $tender->document_path ? url('storage/' . $tender->document_path) : null;

        return response()->json(['success' => true, 'message' => 'İhale güncellendi.', 'data' => $tender]);
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermission('tenders.delete')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($id);

        if ($tender->document_path && Storage::disk('public')->exists($tender->document_path)) {
            Storage::disk('public')->delete($tender->document_path);
        }

        $tender->delete();

        return response()->json(['success' => true, 'message' => 'İhale silindi.']);
    }
}
