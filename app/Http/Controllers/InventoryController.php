<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $productos = Product::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->stock_status === 'bajo', function ($query) {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalProductos = Product::count();

        $productosStockBajo = Product::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )->count();

        $movimientos = InventoryMovement::with(['product', 'user'])
            ->latest()
            ->paginate(10, ['*'], 'movimientos_page');

        return view('inventory.index', compact(
            'productos',
            'totalProductos',
            'productosStockBajo',
            'movimientos'
        ));
    }
}