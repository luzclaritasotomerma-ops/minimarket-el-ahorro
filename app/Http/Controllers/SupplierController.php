<?php

namespace App\Http\Controllers;



use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $suppliers = Supplier::when($buscar, function ($query) use ($buscar) {
            $query->where('business_name', 'like', "%{$buscar}%")
                  ->orWhere('document_number', 'like', "%{$buscar}%");
        })
        ->orderBy('business_name')
        ->paginate(10)
        ->withQueryString();

        return view('suppliers.index', compact('suppliers', 'buscar'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|max:20|unique:suppliers,document_number',
            'business_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'document_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('suppliers', 'document_number')->ignore($supplier->id),
            ],
            'business_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->exists()) {
            return redirect()
                ->route('admin.suppliers.index')
                ->with('error', 'No se puede eliminar porque tiene compras registradas.');
        }

        $supplier->delete();

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}
