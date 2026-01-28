<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query()->with(['creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('service_name', 'like', '%' . $request->search . '%')
                  ->orWhere('service_code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Service::count(),
            'active' => Service::where('status', 'active')->count(),
            'inactive' => Service::where('status', 'inactive')->count(),
        ];
        
        $services = $query->paginate(50);
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'categories' => Service::select('category')->distinct()->whereNotNull('category')->orderBy('category')->pluck('category'),
            'users' => $users,
        ];
        
        return view('services.index', compact('services', 'summary', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $preSelected = [
            'created_by' => $request->get('created_by'),
        ];
        
        return view('services.create', compact('users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_code' => 'required|string|max:255|unique:services',
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:0',
            'fixed_price' => 'nullable|numeric|min:0',
            'pricing_type' => 'required|in:hourly,fixed,custom',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:active,inactive',
            'service_details' => 'nullable|string',
            'estimated_hours' => 'nullable|integer|min:0',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        Service::create($validated);
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        $service->load(['creator']);
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $users = User::where('is_active', true)->get();
        return view('services.edit', compact('service', 'users'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_code' => 'required|string|max:255|unique:services,service_code,' . $service->id,
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:0',
            'fixed_price' => 'nullable|numeric|min:0',
            'pricing_type' => 'required|in:hourly,fixed,custom',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:active,inactive',
            'service_details' => 'nullable|string',
            'estimated_hours' => 'nullable|integer|min:0',
        ]);
        
        $service->update($validated);
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ServicesExport, 'services.xlsx');
    }

    public function exportPdf()
    {
        $services = \App\Models\Service::all();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.services', compact('services'));
        return $pdf->download('services.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ServicesImport, $request->file('file'));
        
        return redirect()->route('services.index')->with('success', 'Services imported successfully.');
    }
}
