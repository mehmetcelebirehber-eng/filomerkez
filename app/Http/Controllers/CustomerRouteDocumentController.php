<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerServiceRoute;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerRouteDocumentController extends Controller
{
    public function index(Customer $customer, CustomerServiceRoute $route, Request $request)
    {
        if (!auth()->user()->hasPermission('customers.view')) {
            abort(403);
        }

        $type = $request->query('type', 'vehicle');
        $documentableType = 'route_' . $type;

        $documents = Document::where('documentable_type', $documentableType)
            ->where('documentable_id', $route->id)
            ->orderBy('id', 'desc')
            ->get();

        $sourceType = $type === 'vehicle' ? \App\Models\Fleet\Vehicle::class : \App\Models\Fleet\Driver::class;
        
        $sourceDocuments = Document::where('documentable_type', $sourceType)
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('id', 'desc')
            ->with('documentable')
            ->get();

        return view('customers.route-documents.index', compact('customer', 'route', 'documents', 'sourceDocuments', 'type'));
    }

    public function store(Request $request, Customer $customer, CustomerServiceRoute $route)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $type = $request->query('type', 'vehicle');

        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'nullable|string|max:255',
            'file' => 'required|file|max:20480',
            'end_date' => 'nullable|date',
        ], [
            'document_name.required' => 'Evrak adı zorunludur.',
            'document_name.max' => 'Evrak adı en fazla 255 karakter olabilir.',
            'file.required' => 'Lütfen bir dosya seçin.',
            'file.file' => 'Seçilen öğe geçerli bir dosya olmalıdır.',
            'file.max' => 'Yüklemek istediğiniz dosya boyutu çok büyük. Lütfen en fazla 20MB (veya sunucu limitiniz kadar) boyutunda bir dosya seçin.',
            'file.uploaded' => 'Dosya yüklenirken sunucu limitlerine (upload_max_filesize) takıldı. Daha küçük bir dosya seçin veya sunucu limitini artırın.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::slug($request->document_name) . '-' . time() . '.' . $extension;
            
            $filePath = $file->storeAs("customers/routes/{$route->id}/{$type}", $filename, 'public');
        }

        Document::create([
            'company_id' => auth()->user()->company_id,
            'documentable_type' => 'route_' . $type,
            'documentable_id' => $route->id,
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'end_date' => $request->end_date,
            'file_path' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Evrak başarıyla yüklendi.');
    }

    public function importFromSource(Request $request, Customer $customer, CustomerServiceRoute $route)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $type = $request->query('type', 'vehicle');
        $sourceType = $type === 'vehicle' ? \App\Models\Fleet\Vehicle::class : \App\Models\Fleet\Driver::class;

        $request->validate([
            'document_id' => 'required|exists:documents,id'
        ]);

        $sourceDoc = Document::where('documentable_type', $sourceType)
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($request->document_id);

        $newFilePath = null;
        
        if ($sourceDoc->file_path && Storage::disk('public')->exists($sourceDoc->file_path)) {
            $extension = pathinfo($sourceDoc->file_path, PATHINFO_EXTENSION);
            $filename = \Illuminate\Support\Str::slug($sourceDoc->document_name) . '-copy-' . time() . '.' . $extension;
            $newFilePath = "customers/routes/{$route->id}/{$type}/" . $filename;
            
            Storage::disk('public')->copy($sourceDoc->file_path, $newFilePath);
        }

        Document::create([
            'company_id' => auth()->user()->company_id,
            'documentable_type' => 'route_' . $type,
            'documentable_id' => $route->id,
            'document_name' => $sourceDoc->document_name,
            'document_type' => $sourceDoc->document_type,
            'issuer_name' => $sourceDoc->issuer_name,
            'start_date' => $sourceDoc->start_date,
            'end_date' => $sourceDoc->end_date,
            'file_path' => $newFilePath,
            'notes' => $sourceDoc->notes,
        ]);

        return redirect()->back()->with('success', 'Evrak başarıyla bu güzergaha kopyalandı.');
    }

    public function destroy(Customer $customer, CustomerServiceRoute $route, Document $document)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        if (!in_array($document->documentable_type, ['route_vehicle', 'route_driver']) || $document->documentable_id != $route->id) {
            abort(404);
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Evrak başarıyla silindi.');
    }
}
