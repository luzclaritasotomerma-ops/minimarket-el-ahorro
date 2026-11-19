<?php

namespace App\Http\Controllers;


use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $customers = Customer::when($buscar, function ($query) use ($buscar) {
            $query->where('names', 'like', "%{$buscar}%")
                  ->orWhere('last_names', 'like', "%{$buscar}%")
                  ->orWhere('document_number', 'like', "%{$buscar}%");
        })
        ->orderBy('names')
        ->paginate(10)
        ->withQueryString();

        return view('customers.index', compact('customers', 'buscar'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'nullable|string|max:20|unique:customers,document_number',
            'names' => 'required|string|max:100',
            'last_names' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'document_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers', 'document_number')->ignore($customer->id),
            ],
            'names' => 'required|string|max:100',
            'last_names' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->sales()->exists()) {
            return redirect()
                ->route('customers.index')
                ->with('error', 'No se puede eliminar porque tiene ventas registradas.');
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
