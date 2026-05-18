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

        $query = Tender::withCount('records')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('institution_name', 'like', "%{$search}%");
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
            'vehicle_details' => 'nullable|string'
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $tender = Tender::create($validated);

        return redirect()->route('tenders.index')->with('success', 'İhale dosyası başarıyla oluşturuldu.');
    }

    public function show(Tender $tender)
    {
        if (!auth()->user()->hasPermission('tenders.view')) {
            abort(403, 'Bu sayfayı görüntüleme yetkiniz yok.');
        }

        $records = $tender->records()->orderBy('tender_date', 'desc')->get();
        return view('tenders.show', compact('tender', 'records'));
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
            'vehicle_details' => 'nullable|string'
        ]);

        $tender->update($validated);
        return redirect()->route('tenders.index')->with('success', 'İhale dosyası güncellendi.');
    }

    public function destroy(Tender $tender)
    {
        if (!auth()->user()->hasPermission('tenders.delete')) {
            abort(403, 'İhale silme yetkiniz yok.');
        }

        foreach($tender->records as $record) {
            if ($record->document_path && Storage::disk('public')->exists($record->document_path)) {
                Storage::disk('public')->delete($record->document_path);
            }
        }
        $tender->delete();

        return redirect()->route('tenders.index')->with('success', 'İhale dosyası başarıyla silindi.');
    }
}
