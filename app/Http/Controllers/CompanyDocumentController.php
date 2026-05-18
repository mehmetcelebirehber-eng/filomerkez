<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CompanyDocumentController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('company_documents.view')) {
            abort(403, 'Bu sayfayı görüntüleme yetkiniz yok.');
        }

        $query = Document::where('documentable_type', \App\Models\Company::class)
            ->where('documentable_id', auth()->user()->company_id)
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('document_name', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%");
            });
        }

        $documents = $query->paginate(15);

        return view('company-documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('company_documents.create')) {
            abort(403, 'Evrak yükleme yetkiniz yok.');
        }

        $request->validate([
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|max:20480',
        ]);

        $filePath = $request->file('file')->store('company-documents', 'public');
        $extension = $request->file('file')->getClientOriginalExtension();
        
        $documentType = 'Diğer';
        $docNameLower = mb_strtolower($request->document_name, 'UTF-8');
        
        if (str_contains($docNameLower, 'vergi')) {
            $documentType = 'Vergi Levhası';
        } elseif (str_contains($docNameLower, 'sicil')) {
            $documentType = 'Sicil Gazetesi';
        } elseif (str_contains($docNameLower, 'imza')) {
            $documentType = 'İmza Sirküsü';
        } elseif (str_contains($docNameLower, 'faaliyet')) {
            $documentType = 'Faaliyet Belgesi';
        }

        $document = Document::create([
            'company_id' => auth()->user()->company_id,
            'documentable_type' => \App\Models\Company::class,
            'documentable_id' => auth()->user()->company_id,
            'document_name' => $request->document_name,
            'document_type' => $documentType,
            'file_path' => $filePath,
        ]);

        // Aktivite Kaydı
        \App\Models\ActivityLog::create([
            'company_id'   => auth()->user()->company_id,
            'user_id'      => auth()->id(),
            'module'       => 'company_documents',
            'action'       => 'created',
            'subject_type' => Document::class,
            'subject_id'   => $document->id,
            'title'        => 'Şirket Evrağı Yüklendi',
            'description'  => "'{$document->document_name}' isimli şirket evrağı yüklendi.",
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Evrak başarıyla yüklendi.');
    }

    public function destroy(Document $company_document)
    {
        if (!auth()->user()->hasPermission('company_documents.delete')) {
            abort(403, 'Evrak silme yetkiniz yok.');
        }

        if ($company_document->documentable_type !== \App\Models\Company::class || $company_document->company_id !== auth()->user()->company_id) {
            abort(404);
        }

        if ($company_document->file_path && Storage::disk('public')->exists($company_document->file_path)) {
            Storage::disk('public')->delete($company_document->file_path);
        }

        $name = $company_document->document_name;
        $company_document->delete();

        // Aktivite Kaydı
        \App\Models\ActivityLog::create([
            'company_id'   => auth()->user()->company_id,
            'user_id'      => auth()->id(),
            'module'       => 'company_documents',
            'action'       => 'deleted',
            'title'        => 'Şirket Evrağı Silindi',
            'description'  => "'{$name}' isimli şirket evrağı silindi.",
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Evrak başarıyla silindi.');
    }

    public function bulkDelete(Request $request)
    {
        if (!auth()->user()->hasPermission('company_documents.delete')) {
            abort(403, 'Evrak silme yetkiniz yok.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:documents,id'
        ]);

        $documents = Document::whereIn('id', $request->ids)
            ->where('documentable_type', \App\Models\Company::class)
            ->where('company_id', auth()->user()->company_id)
            ->get();

        $count = 0;
        foreach ($documents as $document) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->delete();
            $count++;
        }

        if ($count > 0) {
            \App\Models\ActivityLog::create([
                'company_id'   => auth()->user()->company_id,
                'user_id'      => auth()->id(),
                'module'       => 'company_documents',
                'action'       => 'deleted',
                'title'        => 'Şirket Evrakları Toplu Silindi',
                'description'  => "{$count} adet şirket evrağı toplu olarak silindi.",
                'ip_address'   => request()->ip(),
                'user_agent'   => request()->userAgent(),
            ]);
        }

        return redirect()->back()->with('success', "{$count} adet evrak silindi.");
    }

    public function downloadZip(Request $request)
    {
        if (!auth()->user()->hasPermission('company_documents.view')) {
            abort(403, 'Evrak indirme yetkiniz yok.');
        }

        $query = Document::where('documentable_type', \App\Models\Company::class)
            ->where('documentable_id', auth()->user()->company_id)
            ->whereNotNull('file_path');

        if ($request->has('ids') && is_array($request->ids)) {
            $query->whereIn('id', $request->ids);
        }

        $documents = $query->get();

        if ($documents->isEmpty()) {
            return redirect()->back()->with('error', 'İndirilecek evrak bulunamadı.');
        }

        $zipFileName = 'Sirket_Evraklari_' . date('Y_m_d_H_i') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'ZIP dosyası oluşturulamadı.');
        }

        $addedCount = 0;
        foreach ($documents as $doc) {
            if (Storage::disk('public')->exists($doc->file_path)) {
                $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                $safeName = \Illuminate\Support\Str::slug($doc->document_name) . '_' . $doc->id . '.' . $ext;
                $zip->addFile(Storage::disk('public')->path($doc->file_path), $safeName);
                $addedCount++;
            }
        }

        $zip->close();

        if ($addedCount === 0) {
            @unlink($zipPath);
            return redirect()->back()->with('error', 'İndirilebilir fiziksel dosya bulunamadı.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
