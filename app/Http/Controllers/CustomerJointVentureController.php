<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerJointVenture;
use App\Models\CustomerServiceRoute;
use Illuminate\Http\Request;

class CustomerJointVentureController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'routes' => 'nullable|array',
            'routes.*' => 'exists:customer_service_routes,id',
        ]);

        $jointVenture = CustomerJointVenture::create([
            'company_id' => auth()->user()->company_id,
            'customer_id' => $customer->id,
            'company_name' => $request->company_name,
            'tax_number' => $request->tax_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Güzergahları ata
        if ($request->has('routes')) {
            CustomerServiceRoute::whereIn('id', $request->routes)
                ->where('customer_id', $customer->id)
                ->update(['joint_venture_id' => $jointVenture->id]);
        }

        return redirect()->back()->with('success', 'Ortak girişim firması başarıyla eklendi.');
    }

    public function update(Request $request, Customer $customer, $jointVentureId)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $jointVenture = CustomerJointVenture::findOrFail($jointVentureId);

        if ($jointVenture->customer_id != $customer->id) {
            abort(404);
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'routes' => 'nullable|array',
            'routes.*' => 'exists:customer_service_routes,id',
        ]);

        if (empty($jointVenture->access_token)) {
            $jointVenture->access_token = \Illuminate\Support\Str::random(40);
        }

        $jointVenture->update([
            'company_name' => $request->company_name,
            'tax_number' => $request->tax_number,
            'phone' => $request->phone,
            'address' => $request->address,
            'access_token' => $jointVenture->access_token,
        ]);

        // Önce eski atamaları temizle
        CustomerServiceRoute::where('joint_venture_id', $jointVenture->id)
            ->where('customer_id', $customer->id)
            ->update(['joint_venture_id' => null]);

        // Yeni atamaları yap
        if ($request->has('routes')) {
            CustomerServiceRoute::whereIn('id', $request->routes)
                ->where('customer_id', $customer->id)
                ->update(['joint_venture_id' => $jointVenture->id]);
        }

        return redirect()->back()->with('success', 'Ortak girişim firması başarıyla güncellendi.');
    }

    public function destroy(Customer $customer, $jointVentureId)
    {
        if (!auth()->user()->hasPermission('customers.delete')) {
            abort(403);
        }

        $jointVenture = CustomerJointVenture::findOrFail($jointVentureId);

        if ($jointVenture->customer_id != $customer->id) {
            abort(404);
        }

        // Önce atamaları temizle
        CustomerServiceRoute::where('joint_venture_id', $jointVenture->id)
            ->update(['joint_venture_id' => null]);

        $jointVenture->delete();

        return redirect()->back()->with('success', 'Ortak girişim firması başarıyla silindi.');
    }
}
