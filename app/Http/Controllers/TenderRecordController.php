<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenderRecordController extends Controller
{
    public function store(Request $request, Tender $tender)
    {
        if (!auth()->user()->hasPermission('tenders.create')) {
            abort(403, 'İhale kaydı ekleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'tender_date' => 'required|date',
            'tender_registration_number' => 'nullable|string|max:255',
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

        $tender->records()->create($validated);

        return redirect()->route('tenders.show', $tender)->with('success', 'İhale geçmiş kaydı başarıyla eklendi.');
    }

    public function update(Request $request, Tender $tender, TenderRecord $record)
    {
        if (!auth()->user()->hasPermission('tenders.edit')) {
            abort(403, 'İhale kaydı düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'tender_date' => 'required|date',
            'tender_registration_number' => 'nullable|string|max:255',
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
            if ($record->document_path && Storage::disk('public')->exists($record->document_path)) {
                Storage::disk('public')->delete($record->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('tenders', 'public');
        }

        $record->update($validated);

        return redirect()->route('tenders.show', $tender)->with('success', 'İhale geçmiş kaydı başarıyla güncellendi.');
    }

    public function destroy(Tender $tender, TenderRecord $record)
    {
        if (!auth()->user()->hasPermission('tenders.delete')) {
            abort(403, 'İhale kaydı silme yetkiniz yok.');
        }

        if ($record->document_path && Storage::disk('public')->exists($record->document_path)) {
            Storage::disk('public')->delete($record->document_path);
        }

        $record->delete();

        return redirect()->route('tenders.show', $tender)->with('success', 'İhale geçmiş kaydı silindi.');
    }
}
