<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with(['creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('product_code', 'like', '%' . $request->search . '%')
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
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
        ];
        
        $products = $query->paginate(50);
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'categories' => Product::select('category')->distinct()->whereNotNull('category')->orderBy('category')->pluck('category'),
            'users' => $users,
        ];
        
        return view('products.index', compact('products', 'summary', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $preSelected = [
            'created_by' => $request->get('created_by'),
        ];
        
        return view('products.create', compact('users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|max:255|unique:products',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'stock_quantity' => 'nullable|integer|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:active,inactive',
            'specifications' => 'nullable|string',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['creator']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $users = User::where('is_active', true)->get();
        return view('products.edit', compact('product', 'users'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|max:255|unique:products,product_code,' . $product->id,
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'stock_quantity' => 'nullable|integer|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:active,inactive',
            'specifications' => 'nullable|string',
        ]);
        
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProductsExport, 'products.xlsx');
    }

    public function exportPdf()
    {
        $products = \App\Models\Product::all();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.products', compact('products'));
        return $pdf->download('products.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ProductsImport, $request->file('file'));
        
        return redirect()->route('products.index')->with('success', 'Products imported successfully.');
    }
}
