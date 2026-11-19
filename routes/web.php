
<?php
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas exclusivas del administrador
    Route::middleware(['role:Administrador'])->prefix('admin')->group(function () {

        Route::get('/panel', function () {
    return redirect()->route('dashboard');
})->name('admin.panel');
        
        Route::resource('categories', CategoryController::class)
    ->names('admin.categories');

    Route::resource('products', ProductController::class)
    ->names('admin.products');

    Route::resource('suppliers', SupplierController::class)
    ->names('admin.suppliers');

    Route::get('/purchases', [PurchaseController::class, 'index'])
    ->name('admin.purchases.index');

Route::get('/purchases/create', [PurchaseController::class, 'create'])
    ->name('admin.purchases.create');

Route::post('/purchases', [PurchaseController::class, 'store'])
    ->name('admin.purchases.store');

Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])
    ->name('admin.purchases.show');

Route::get('/reports', [ReportController::class, 'index'])
    ->name('admin.reports.index');

Route::resource('users', UserController::class)
    ->names('admin.users');
    });

    // Rutas para administrador y vendedor
    Route::middleware(['role:Administrador,Vendedor'])->group(function () {

       Route::get('/ventas', function () {
    return redirect()->route('sales.index');
})->name('ventas.index');

Route::get('/clientes', function () {
    return redirect()->route('customers.index');
})->name('clientes.index');

        Route::resource('customers', CustomerController::class)
    ->names('customers');
Route::get('/sales', [SaleController::class, 'index'])
    ->name('sales.index');

Route::get('/sales/create', [SaleController::class, 'create'])
    ->name('sales.create');

Route::post('/sales', [SaleController::class, 'store'])
    ->name('sales.store');

Route::get('/sales/{sale}', [SaleController::class, 'show'])
    ->name('sales.show');

Route::middleware(['auth', 'role:Administrador,Vendedor'])->group(function () {

    Route::get('/inventory', [InventoryController::class, 'index'])
        ->name('inventory.index');

});

    });

});

require __DIR__.'/auth.php';
