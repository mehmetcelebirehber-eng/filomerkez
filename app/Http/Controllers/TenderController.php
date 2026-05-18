<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('tenders.view')) {
            abort(403, 'Bu sayfayı görüntüleme yetkiniz yok.');
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

        $tenders = $query->paginate(15);

        return view('tenders.index', compact('tenders'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('tenders.create')) {
            abort(403, 'İhale ekleme yetkiniz yok.');
        }

        return view('tenders.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('tenders.create')) {
            abort(403, 'İhale ekleme yetkiniz yok.');
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

        return redirect()->route('tenders.index')->with('success', 'İhale kaydı başarıyla oluşturuldu.');
    }

    public function edit(Tender $tender)
    {
        if (!auth()->user()->hasPermission('tenders.edit')) {
            abort(403, 'İhale düzenleme yetkiniz yok.');
        }

        return view('tenders.edit', compact('tender'));
    }

    public function update(Request $request, Tender $tender)
    {
        if (!auth()->user()->hasPermission('tenders.edit')) {
            abort(403, 'İhale düzenleme yetkiniz yok.');
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
            if ($tender->document_path && Storage::disk('public')->exists($tender->document_path)) {
                Storage::disk('public')->delete($tender->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('tenders', 'public');
        }

        $tender->update($validated);

        return redirect()->route('tenders.index')->with('success', 'İhale başarıyla güncellendi.');
    }

    public function destroy(Tender $tender)
    {
        if (!auth()->user()->hasPermission('tenders.delete')) {
            abort(403, 'İhale silme yetkiniz yok.');
        }

        if ($tender->document_path && Storage::disk('public')->exists($tender->document_path)) {
            Storage::disk('public')->delete($tender->document_path);
        }

        $tender->delete();

        return redirect()->route('tenders.index')->with('success', 'İhale başarıyla silindi.');
    }
}
