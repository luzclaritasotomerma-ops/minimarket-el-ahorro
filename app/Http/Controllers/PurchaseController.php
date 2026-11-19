<?php

namespace App\Http\Controllers;


use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $purchases = Purchase::with(['supplier', 'user'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('purchase_number', 'like', "%{$buscar}%")
                    ->orWhereHas('supplier', function ($supplierQuery) use ($buscar) {
                        $supplierQuery->where('business_name', 'like', "%{$buscar}%");
                    });
            })
            ->orderByDesc('purchase_date')
            ->paginate(10)
            ->withQueryString();

        return view('purchases.index', compact('purchases', 'buscar'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', true)
            ->orderBy('business_name')
            ->get();

        $products = Product::where('status', true)
            ->orderBy('name')
            ->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'observations' => 'nullable|string|max:1000',

            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unit_price' => 'required|numeric|gt:0',
        ]);

        try {
            $purchase = DB::transaction(function () use ($validated) {

                $purchaseNumber = 'COMP-' . now()->format('YmdHis')
                    . '-' . Str::upper(Str::random(4));

                $total = 0;

                foreach ($validated['details'] as $detail) {
                    $total += $detail['quantity'] * $detail['unit_price'];
                }

                $purchase = Purchase::create([
                    'supplier_id' => $validated['supplier_id'],
                    'user_id' => auth()->id(),
                    'purchase_number' => $purchaseNumber,
                    'purchase_date' => $validated['purchase_date'],
                    'total' => $total,
                    'status' => 'Completada',
                    'observations' => $validated['observations'] ?? null,
                ]);

                foreach ($validated['details'] as $detail) {

                    $product = Product::whereKey($detail['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    $stockBefore = $product->stock;
                    $quantity = $detail['quantity'];
                    $unitPrice = $detail['unit_price'];
                    $subtotal = $quantity * $unitPrice;

                    PurchaseDetail::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    $product->stock = $stockBefore + $quantity;
                    $product->save();

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'entrada',
                        'quantity' => $quantity,
                        'stock_before' => $stockBefore,
                        'stock_after' => $product->stock,
                        'reference_type' => 'Compra',
                        'reference_id' => $purchase->id,
                        'description' => 'Entrada por compra ' . $purchaseNumber,
                    ]);
                }

                return $purchase;
            });

            return redirect()
                ->route('admin.purchases.show', $purchase)
                ->with('success', 'Compra registrada y stock actualizado correctamente.');

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar la compra. Verifica los datos e inténtalo nuevamente.');
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'details.product']);

        return view('purchases.show', compact('purchase'));
    }
}
