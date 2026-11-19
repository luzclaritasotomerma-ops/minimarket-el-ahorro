<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Mostrar categorías
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $categories = Category::when($buscar, function ($query, $buscar) {
            $query->where('name', 'like', "%{$buscar}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('categories.index', compact('categories', 'buscar'));
    }

    // Mostrar formulario de registro
    public function create()
    {
        return view('categories.create');
    }

    // Guardar categoría
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.unique' => 'Esta categoría ya está registrada.',
            'name.max' => 'El nombre no debe superar los 100 caracteres.',
        ]);

        Category::create($request->only([
            'name',
            'description',
            'status',
        ]));

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría registrada correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Actualizar categoría
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $category->update($request->only([
            'name',
            'description',
            'status',
        ]));

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    // Eliminar categoría
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'No se puede eliminar una categoría que tiene productos registrados.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
