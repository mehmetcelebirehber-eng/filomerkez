<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerCompanyDocumentController extends Controller
{
    public function index(Customer $customer)
    {
        if (!auth()->user()->hasPermission('customers.view')) {
            abort(403);
        }

        // Müşteriye tanımlı (Firma Evrakları) belgeler
        $documents = Document::where('documentable_type', Customer::class)
            ->where('documentable_id', $customer->id)
            ->orderBy('id', 'desc')
            ->get();

        // Şirket evraklarından seçebilmek için kullanıcının şirket evraklarını da alalım
        $companyDocuments = Document::where('documentable_type', \App\Models\Company::class)
            ->where('documentable_id', auth()->user()->company_id)
            ->orderBy('id', 'desc')
            ->get();

        return view('customers.company-documents.index', compact('customer', 'documents', 'companyDocuments'));
    }

    public function store(Request $request, Customer $customer)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'nullable|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'end_date' => 'nullable|date',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::slug($request->document_name) . '-' . time() . '.' . $extension;
            
            // customers/documents/{customerId} dizinine kaydet
            $filePath = $file->storeAs('customers/documents/' . $customer->id, $filename, 'public');
        }

        Document::create([
            'company_id' => auth()->user()->company_id,
            'documentable_type' => Customer::class,
            'documentable_id' => $customer->id,
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'end_date' => $request->end_date,
            'file_path' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Evrak başarıyla yüklendi.');
    }

    public function importFromCompany(Request $request, Customer $customer)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $request->validate([
            'document_id' => 'required|exists:documents,id'
        ]);

        $companyDoc = Document::where('documentable_type', \App\Models\Company::class)
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($request->document_id);

        $newFilePath = null;
        
        // Fiziksel dosyayı da kopyalayalım ki orjinali silinirse bu bozulmasın
        if ($companyDoc->file_path && Storage::disk('public')->exists($companyDoc->file_path)) {
            $extension = pathinfo($companyDoc->file_path, PATHINFO_EXTENSION);
            $filename = \Illuminate\Support\Str::slug($companyDoc->document_name) . '-copy-' . time() . '.' . $extension;
            $newFilePath = 'customers/documents/' . $customer->id . '/' . $filename;
            
            Storage::disk('public')->copy($companyDoc->file_path, $newFilePath);
        }

        Document::create([
            'company_id' => auth()->user()->company_id,
            'documentable_type' => Customer::class,
            'documentable_id' => $customer->id,
            'document_name' => $companyDoc->document_name,
            'document_type' => $companyDoc->document_type,
            'issuer_name' => $companyDoc->issuer_name,
            'start_date' => $companyDoc->start_date,
            'end_date' => $companyDoc->end_date,
            'file_path' => $newFilePath,
            'notes' => $companyDoc->notes,
        ]);

        return redirect()->back()->with('success', 'Şirket evrağı başarıyla bu müşteriye kopyalandı.');
    }

    public function destroy(Customer $customer, Document $document)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        if ($document->documentable_type !== Customer::class || $document->documentable_id !== $customer->id) {
            abort(404);
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Evrak başarıyla silindi.');
    }
}
