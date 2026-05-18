<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use App\Models\TenderRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenderApiController extends Controller
{
    /* ==============================
       TENDER (FOLDER) ENDPOINTS
       ============================== */

    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('tenders.view')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $query = Tender::withCount('records')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('institution_name', 'like', "%{$search}%");
        }

        $tenders = $query->get();

        // Calculate overall stats from all records of this company
        $companyId = auth()->user()->company_id;
        $totalRecords = TenderRecord::whereHas('tender', fn($q) => $q->where('company_id', $companyId))->count();
        $wonRecords = TenderRecord::whereHas('tender', fn($q) => $q->where('company_id', $companyId))->where('status', 'Kazanıldı')->count();
        $lostRecords = TenderRecord::whereHas('tender', fn($q) => $q->where('company_id', $companyId))->where('status', 'Kaybedildi')->count();
        $evalRecords = TenderRecord::whereHas('tender', fn($q) => $q->where('company_id', $companyId))->where('status', 'Değerlendirmede')->count();

        return response()->json([
            'success' => true,
            'data' => $tenders,
            'stats' => [
                'total' => $totalRecords,
                'won' => $wonRecords,
                'lost' => $lostRecords,
                'evaluating' => $evalRecords
            ]
        ]);
    }

    public function show($id)
    {
        if (!auth()->user()->hasPermission('tenders.view')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::with(['records' => function($q) {
            $q->orderBy('tender_date', 'desc');
        }])->findOrFail($id);

        // Add file url to records
        $tender->records->transform(function ($record) {
            $record->file_url = $record->document_path ? url('storage/' . $record->document_path) : null;
            return $record;
        });

        return response()->json(['success' => true, 'data' => $tender]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('tenders.create')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'vehicle_details' => 'nullable|string'
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        $tender = Tender::create($validated);

        return response()->json(['success' => true, 'message' => 'İhale dosyası oluşturuldu.', 'data' => $tender]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('tenders.edit')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($id);

        $validated = $request->validate([
            'institution_name' => 'sometimes|required|string|max:255',
            'vehicle_details' => 'nullable|string'
        ]);

        $tender->update($validated);

        return response()->json(['success' => true, 'message' => 'İhale dosyası güncellendi.', 'data' => $tender]);
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermission('tenders.delete')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($id);

        foreach($tender->records as $record) {
            if ($record->document_path && Storage::disk('public')->exists($record->document_path)) {
                Storage::disk('public')->delete($record->document_path);
            }
        }
        $tender->delete();

        return response()->json(['success' => true, 'message' => 'İhale dosyası silindi.']);
    }

    /* ==============================
       TENDER RECORDS ENDPOINTS
       ============================== */

    private function validateRecord(Request $request)
    {
        $validated = $request->validate([
            'tender_date' => 'sometimes|required|date',
            'tender_registration_number' => 'nullable|string|max:255',
            'duration_days' => 'nullable|integer',
            
            'total_vehicles' => 'nullable|integer',
            'minibus_count' => 'nullable|integer',
            'midibus_count' => 'nullable|integer',
            'bus_count' => 'nullable|integer',
            'taxi_count' => 'nullable|integer',
            'vehicle_model_requirement' => 'nullable|string|max:255',
            
            'approximate_cost' => 'nullable|numeric',
            'our_bid' => 'nullable|numeric',
            
            'bids' => 'nullable|array',
            'bids.*.company_name' => 'required_with:bids|string',
            'bids.*.bid_amount' => 'required_with:bids|numeric',
            
            'winning_company' => 'nullable|string|max:255',
            'winning_amount' => 'nullable|numeric',
            'winning_unit_price' => 'nullable|numeric',
            
            'status' => 'sometimes|required|in:Değerlendirmede,Kazanıldı,Kaybedildi,İptal',
            'document' => 'nullable|file|max:20480',
            'notes' => 'nullable|string'
        ]);

        if (isset($validated['bids'])) {
            $validated['bids'] = array_values($validated['bids']);
        }

        return $validated;
    }

    public function storeRecord(Request $request, $tenderId)
    {
        if (!auth()->user()->hasPermission('tenders.create')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($tenderId);

        $validated = $this->validateRecord($request);

        if ($request->hasFile('document')) {
            $validated['document_path'] = $request->file('document')->store('tenders', 'public');
        }

        $record = $tender->records()->create($validated);
        $record->file_url = $record->document_path ? url('storage/' . $record->document_path) : null;

        return response()->json(['success' => true, 'message' => 'Geçmiş kayıt eklendi.', 'data' => $record]);
    }

    public function updateRecord(Request $request, $tenderId, $recordId)
    {
        if (!auth()->user()->hasPermission('tenders.edit')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($tenderId);
        $record = $tender->records()->findOrFail($recordId);

        $validated = $this->validateRecord($request);

        if ($request->hasFile('document')) {
            if ($record->document_path && Storage::disk('public')->exists($record->document_path)) {
                Storage::disk('public')->delete($record->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('tenders', 'public');
        }

        $record->update($validated);
        $record->file_url = $record->document_path ? url('storage/' . $record->document_path) : null;

        return response()->json(['success' => true, 'message' => 'Geçmiş kayıt güncellendi.', 'data' => $record]);
    }

    public function destroyRecord($tenderId, $recordId)
    {
        if (!auth()->user()->hasPermission('tenders.delete')) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $tender = Tender::findOrFail($tenderId);
        $record = $tender->records()->findOrFail($recordId);

        if ($record->document_path && Storage::disk('public')->exists($record->document_path)) {
            Storage::disk('public')->delete($record->document_path);
        }

        $record->delete();

        return response()->json(['success' => true, 'message' => 'Geçmiş kayıt silindi.']);
    }
}
