<?php

namespace App\Http\Controllers;

use App\Models\CustomerJointVenture;
use App\Models\CustomerServiceRoute;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JointVenturePortalController extends Controller
{
    private function getJointVenture(string $token)
    {
        return CustomerJointVenture::where('access_token', $token)->firstOrFail();
    }

    public function index($token)
    {
        $jv = $this->getJointVenture($token);
        // eager load routes to show them
        $jv->load('serviceRoutes');

        return view('joint-ventures.portal.index', compact('jv'));
    }

    public function documents($token, $routeId, Request $request)
    {
        $jv = $this->getJointVenture($token);
        
        // Sadece kendi rotasına işlem yapabilir
        $route = CustomerServiceRoute::where('id', $routeId)
            ->where('joint_venture_id', $jv->id)
            ->firstOrFail();

        $type = $request->get('type', 'vehicle'); // vehicle or driver
        $isVehicle = $type === 'vehicle';
        $documentableType = 'route_' . $type;

        $documents = Document::where('documentable_type', $documentableType)
            ->where('documentable_id', $route->id)
            ->get();

        return view('joint-ventures.portal.documents', compact('jv', 'route', 'type', 'documents', 'isVehicle'));
    }

    public function storeDocument(Request $request, $token, $routeId)
    {
        $jv = $this->getJointVenture($token);
        
        $route = CustomerServiceRoute::where('id', $routeId)
            ->where('joint_venture_id', $jv->id)
            ->firstOrFail();

        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'nullable|string|max:255',
            'end_date' => 'nullable|date',
            'file' => 'required|file|max:20480', // max 20mb
            'type' => 'required|in:vehicle,driver',
        ]);

        $file = $request->file('file');
        $path = $file->store('customer_documents', 'public');

        Document::create([
            'company_id' => $jv->company_id,
            'documentable_type' => 'route_' . $request->type,
            'documentable_id' => $route->id,
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'end_date' => $request->end_date,
            'file_path' => $path,
        ]);

        return redirect()->back()->with('success', 'Evrak başarıyla yüklendi.');
    }

    public function destroyDocument($token, $routeId, Document $document)
    {
        $jv = $this->getJointVenture($token);
        
        $route = CustomerServiceRoute::where('id', $routeId)
            ->where('joint_venture_id', $jv->id)
            ->firstOrFail();

        // Evrak bu güzergaha mı ait?
        if (!in_array($document->documentable_type, ['route_vehicle', 'route_driver']) || $document->documentable_id !== $route->id) {
            abort(403);
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Evrak başarıyla silindi.');
    }
}
