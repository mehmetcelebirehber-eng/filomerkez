<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleTrackingSetting;
use App\Services\ArventoService;

class VehicleTrackingApiController extends Controller
{
    public function live(Request $request)
    {
        // Kullanıcının araçları görme yetkisi var mı?
        abort_unless($request->user()->hasPermission('vehicles.view'), 403, 'Bu işlem için yetkiniz bulunmamaktadır.');

        $companyId = $request->user()->company_id;
        $setting = VehicleTrackingSetting::where('company_id', $companyId)->where('is_active', true)->first();
        
        $vehicles = [];
        if ($setting && $setting->provider === 'arvento') {
            $arvento = new ArventoService($setting);
            $vehicles = $arvento->getVehicleStatus();
        }

        return response()->json([
            'success' => true,
            'vehicles' => $vehicles,
            'provider_active' => $setting ? true : false,
            'provider_name' => $setting ? $setting->provider : null
        ]);
    }
}
