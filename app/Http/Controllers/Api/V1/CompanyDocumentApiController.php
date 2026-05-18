<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CompanyDocumentApiController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('company_documents.view')) {
            return response()->json(['message' => 'Bu veriyi görüntüleme yetkiniz yok.'], 403);
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

        $perPage = $request->input('per_page', 15);
        $documents = $query->paginate($perPage);

        $documents->getCollection()->transform(function ($doc) {
            $doc->file_url = $doc->file_path ? url('storage/' . $doc->file_path) : null;
            return $doc;
        });

        return response()->json($documents);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('company_documents.create')) {
            return response()->json(['message' => 'Evrak yükleme yetkiniz yok.'], 403);
        }

        $request->validate([
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|max:20480',
        ]);

        $filePath = $request->file('file')->store('company-documents', 'public');
        
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

        $document->file_url = url('storage/' . $document->file_path);

        \App\Models\ActivityLog::create([
            'company_id'   => auth()->user()->company_id,
            'user_id'      => auth()->id(),
            'module'       => 'company_documents',
            'action'       => 'created',
            'subject_type' => Document::class,
            'subject_id'   => $document->id,
            'title'        => 'Şirket Evrağı Yüklendi (Mobil)',
            'description'  => "'{$document->document_name}' isimli şirket evrağı mobil üzerinden yüklendi.",
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);

        return response()->json([
            'message' => 'Evrak başarıyla yüklendi.',
            'document' => $document
        ], 201);
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermission('company_documents.delete')) {
            return response()->json(['message' => 'Evrak silme yetkiniz yok.'], 403);
        }

        $document = Document::where('documentable_type', \App\Models\Company::class)
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $name = $document->document_name;
        $document->delete();

        \App\Models\ActivityLog::create([
            'company_id'   => auth()->user()->company_id,
            'user_id'      => auth()->id(),
            'module'       => 'company_documents',
            'action'       => 'deleted',
            'title'        => 'Şirket Evrağı Silindi (Mobil)',
            'description'  => "'{$name}' isimli şirket evrağı mobil üzerinden silindi.",
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);

        return response()->json(['message' => 'Evrak başarıyla silindi.']);
    }

    public function bulkDelete(Request $request)
    {
        if (!auth()->user()->hasPermission('company_documents.delete')) {
            return response()->json(['message' => 'Evrak silme yetkiniz yok.'], 403);
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
                'title'        => 'Şirket Evrakları Toplu Silindi (Mobil)',
                'description'  => "{$count} adet şirket evrağı mobil üzerinden toplu olarak silindi.",
                'ip_address'   => request()->ip(),
                'user_agent'   => request()->userAgent(),
            ]);
        }

        return response()->json(['message' => "{$count} adet evrak silindi."]);
    }

    public function downloadZip(Request $request)
    {
        // For API, usually downloading a file directly is tricky via JSON endpoints,
        // but we can return the URL to the web download route or stream it.
        // Returning a direct response download is fine if the mobile app can handle it.
        if (!auth()->user()->hasPermission('company_documents.view')) {
            return response()->json(['message' => 'Evrak indirme yetkiniz yok.'], 403);
        }

        $query = Document::where('documentable_type', \App\Models\Company::class)
            ->where('documentable_id', auth()->user()->company_id)
            ->whereNotNull('file_path');

        if ($request->has('ids') && is_array($request->ids)) {
            $query->whereIn('id', $request->ids);
        }

        $documents = $query->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'İndirilecek evrak bulunamadı.'], 404);
        }

        $zipFileName = 'Sirket_Evraklari_' . date('Y_m_d_H_i') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return response()->json(['message' => 'ZIP dosyası oluşturulamadı.'], 500);
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
            return response()->json(['message' => 'İndirilebilir fiziksel dosya bulunamadı.'], 404);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
