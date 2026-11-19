<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // Mostrar productos y realizar búsquedas
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $categoryId = $request->input('category_id');

        $products = Product::with('category')
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                      ->orWhere('code', 'like', "%{$buscar}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact(
            'products',
            'categories',
            'buscar',
            'categoryId'
        ));
    }

    // Mostrar formulario de registro
    public function create()
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories'));
    }

    // Guardar producto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:50|unique:products,code',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|gt:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:30',
            'status' => 'required|boolean',
        ], [
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'code.required' => 'El código del producto es obligatorio.',
            'code.unique' => 'Este código ya está registrado.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'purchase_price.min' => 'El precio de compra no puede ser negativo.',
            'sale_price.gt' => 'El precio de venta debe ser mayor que cero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'minimum_stock.min' => 'El stock mínimo no puede ser negativo.',
        ]);

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact(
            'product',
            'categories'
        ));
    }

    // Actualizar producto
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'code')->ignore($product->id),
            ],
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|gt:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:30',
            'status' => 'required|boolean',
        ]);

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    // Eliminar producto
    public function destroy(Product $product)
    {
        if (
            $product->purchaseDetails()->exists() ||
            $product->saleDetails()->exists() ||
            $product->inventoryMovements()->exists()
        ) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'No se puede eliminar este producto porque tiene movimientos registrados.');
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
