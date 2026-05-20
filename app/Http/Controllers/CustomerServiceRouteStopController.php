<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerServiceRoute;
use App\Models\CustomerRouteStop;
use Illuminate\Http\Request;

class CustomerServiceRouteStopController extends Controller
{
    public function index(Customer $customer, CustomerServiceRoute $route)
    {
        if (!auth()->user()->hasPermission('customers.view')) {
            abort(403);
        }

        $stops = $route->stops;

        return view('customers.stops.index', compact('customer', 'route', 'stops'));
    }

    public function store(Request $request, Customer $customer, CustomerServiceRoute $route)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $request->validate([
            'stops' => 'nullable|array',
            'stops.*.stop_name' => 'required|string|max:255',
            'stops.*.stop_time' => 'nullable|date_format:H:i',
        ]);

        // Clear existing stops and re-insert them to keep order cleanly
        $route->stops()->delete();

        if ($request->has('stops') && is_array($request->stops)) {
            $order = 1;
            foreach ($request->stops as $stopData) {
                $route->stops()->create([
                    'stop_name' => $stopData['stop_name'],
                    'stop_time' => $stopData['stop_time'] ?? null,
                    'stop_order' => $order++,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('customers.service-routes.stops.index', [$customer, $route])
            ->with('success', 'Güzergah durakları başarıyla güncellendi.');
    }

    public function import(Request $request, Customer $customer, CustomerServiceRoute $route)
    {
        if (!auth()->user()->hasPermission('customers.edit')) {
            abort(403);
        }

        $request->validate([
            'excel_file' => 'required|mimes:csv,txt,xlsx,xls|max:5120'
        ]);

        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');
            
            try {
                $import = new class implements \Maatwebsite\Excel\Concerns\ToArray {
                    public function array(array $array) { }
                };
                
                $data = \Maatwebsite\Excel\Facades\Excel::toArray($import, $file)[0] ?? [];
            } catch (\Exception $e) {
                return back()->with('error', 'Dosya formatı okunamadı. Lütfen geçerli bir Excel veya CSV dosyası yükleyin.');
            }
            
            $start = 0;
            if (count($data) > 0 && isset($data[0][0]) && stripos((string)$data[0][0], 'Durak') !== false) {
                $start = 1;
            }

            $currentMaxOrder = $route->stops()->max('stop_order') ?? 0;
            $order = $currentMaxOrder + 1;

            foreach (array_slice($data, $start) as $row) {
                if (!isset($row[0]) || empty(trim((string)$row[0]))) continue;

                $stopName = trim((string)$row[0]);
                $stopTime = isset($row[1]) ? trim((string)$row[1]) : null;
                
                if ($stopTime) {
                    // Normalize time if excel parsing parsed it weirdly or returned a decimal
                    if (is_numeric($stopTime) && $stopTime < 1) {
                        $stopTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($stopTime)->format('H:i');
                    } elseif (!preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $stopTime)) {
                        $stopTime = null; 
                    }
                }

                $route->stops()->create([
                    'stop_name' => $stopName,
                    'stop_time' => $stopTime,
                    'stop_order' => $order++,
                    'is_active' => true,
                ]);
            }

            return redirect()->route('customers.service-routes.stops.index', [$customer, $route])
                ->with('success', 'Duraklar dosyadan başarıyla içe aktarıldı.');
        }

        return back()->with('error', 'Dosya bulunamadı.');
    }

    public function export(Customer $customer, CustomerServiceRoute $route)
    {
        if (!auth()->user()->hasPermission('customers.view')) {
            abort(403);
        }

        $stops = $route->stops;
        $fileName = \Illuminate\Support\Str::slug($route->route_name) . '-duraklari.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CustomerRouteStopsExport($stops, $route->route_name),
            $fileName
        );
    }
}
