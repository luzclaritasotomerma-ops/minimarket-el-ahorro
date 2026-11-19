<?php

namespace App\Http\Controllers;



use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date|after_or_equal:desde',
        ]);

        $desde = $request->input(
            'desde',
            now()->startOfMonth()->toDateString()
        );

        $hasta = $request->input(
            'hasta',
            now()->toDateString()
        );

        // Consulta de ventas por fechas
        $ventasQuery = Sale::whereBetween('sale_date', [
            $desde,
            $hasta
        ]);

        $totalVentas = (clone $ventasQuery)->count();

        $totalIngresos = (clone $ventasQuery)->sum('total');

        $ventas = (clone $ventasQuery)
            ->with(['customer', 'user'])
            ->orderByDesc('sale_date')
            ->paginate(10)
            ->withQueryString();

        // Productos más vendidos
        $productosMasVendidos = SaleDetail::query()
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as cantidad_vendida'),
                DB::raw('SUM(subtotal) as total_vendido')
            )
            ->whereHas('sale', function ($query) use ($desde, $hasta) {
                $query->whereBetween('sale_date', [
                    $desde,
                    $hasta
                ]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('cantidad_vendida')
            ->limit(10)
            ->get();

        // Productos con stock bajo
        $productosStockBajo = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->get();

        $cantidadStockBajo = $productosStockBajo->count();

        return view('reports.index', compact(
            'desde',
            'hasta',
            'totalVentas',
            'totalIngresos',
            'ventas',
            'productosMasVendidos',
            'productosStockBajo',
            'cantidadStockBajo'
        ));
    }
}
