<?php

namespace App\Http\Controllers;


use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $sales = Sale::with(['customer', 'user'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('sale_number', 'like', "%{$buscar}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($buscar) {
                        $customerQuery->where('names', 'like', "%{$buscar}%")
                            ->orWhere('last_names', 'like', "%{$buscar}%");
                    });
            })
            ->orderByDesc('sale_date')
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', compact('sales', 'buscar'));
    }

    public function create()
    {
        $customers = Customer::orderBy('names')->get();

        $products = Product::where('status', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'payment_method' => 'required|in:Efectivo,Transferencia,Tarjeta,Yape',
'payment_verified' => 'required_if:payment_method,Yape|accepted',
            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|distinct|exists:products,id',
            'details.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $sale = DB::transaction(function () use ($validated) {

                $saleNumber = 'VENT-' . now()->format('YmdHis')
                    . '-' . Str::upper(Str::random(4));

                $subtotalGeneral = 0;
                $productosVenta = [];

                // Bloquear los productos mientras se verifica el stock.
                foreach ($validated['details'] as $detail) {
                    $product = Product::whereKey($detail['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (!$product->status) {
                        throw new \RuntimeException(
                            'Uno de los productos seleccionados está inactivo.'
                        );
                    }

                    $quantity = (int) $detail['quantity'];

                    if ($product->stock < $quantity) {
                        throw new \RuntimeException(
                            'Stock insuficiente para el producto: ' . $product->name .
                            '. Stock disponible: ' . $product->stock
                        );
                    }

                    $unitPrice = (float) $product->sale_price;
                    $lineSubtotal = $quantity * $unitPrice;

                    $subtotalGeneral += $lineSubtotal;

                    $productosVenta[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $lineSubtotal,
                    ];
                }

                // En esta versión el impuesto se registra en cero.
                $tax = 0;
                $total = $subtotalGeneral + $tax;

                $sale = Sale::create([
                    'customer_id' => $validated['customer_id'] ?? null,
                    'user_id' => auth()->id(),
                    'sale_number' => $saleNumber,
                    'sale_date' => $validated['sale_date'],
                    'subtotal' => $subtotalGeneral,
                    'tax' => $tax,
                    'total' => $total,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'Completada',
                ]);

                foreach ($productosVenta as $item) {
                    $product = $item['product'];
                    $stockBefore = $product->stock;

                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $product->stock = $stockBefore - $item['quantity'];
                    $product->save();

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'salida',
                        'quantity' => $item['quantity'],
                        'stock_before' => $stockBefore,
                        'stock_after' => $product->stock,
                        'reference_type' => 'Venta',
                        'reference_id' => $sale->id,
                        'description' => 'Salida por venta ' . $saleNumber,
                    ]);
                }

                return $sale;
            });

            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Venta registrada y stock actualizado correctamente.');

        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar la venta. Verifica los datos e inténtalo nuevamente.');
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'details.product']);

        return view('sales.show', compact('sale'));
    }
}
