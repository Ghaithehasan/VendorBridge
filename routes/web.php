<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\RfqController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('purchase-requests')
        ->name('purchase-requests.')
        ->group(function () {
            Route::get('/', [PurchaseRequestController::class, 'index'])->name('index');

            Route::get('/create', [PurchaseRequestController::class, 'create'])
                ->middleware('role:requester,admin')
                ->name('create');

            Route::post('/', [PurchaseRequestController::class, 'store'])
                ->middleware('role:requester,admin')
                ->name('store');

            Route::get('/{purchaseRequest:pr_id}', [PurchaseRequestController::class, 'show'])
                ->name('show');

            Route::get('/{purchaseRequest:pr_id}/edit', [PurchaseRequestController::class, 'edit'])
                ->middleware('role:requester,admin')
                ->name('edit');

            Route::put('/{purchaseRequest:pr_id}', [PurchaseRequestController::class, 'update'])
                ->middleware('role:requester,admin')
                ->name('update');

            Route::patch('/{purchaseRequest:pr_id}/submit', [PurchaseRequestController::class, 'submit'])
                ->middleware('role:requester,admin')
                ->name('submit');

            Route::patch('/{purchaseRequest:pr_id}/approve', [PurchaseRequestController::class, 'approve'])
                ->middleware('role:procurement_manager,admin')
                ->name('approve');

            Route::patch('/{purchaseRequest:pr_id}/cancel', [PurchaseRequestController::class, 'cancel'])
                ->middleware('role:procurement_manager,admin')
                ->name('cancel');
        });

    Route::prefix('rfqs')
        ->name('rfqs.')
        ->group(function () {
            Route::get('/', [RfqController::class, 'index'])->name('index');

            Route::post('/', [RfqController::class, 'store'])
                ->middleware('role:purchasing_officer,admin')
                ->name('store');

            Route::get('/{rfq:rfq_id}', [RfqController::class, 'show'])
                ->name('show');

            Route::get('/{rfq:rfq_id}/edit', [RfqController::class, 'edit'])
                ->middleware('role:purchasing_officer,admin')
                ->name('edit');

            Route::put('/{rfq:rfq_id}', [RfqController::class, 'update'])
                ->middleware('role:purchasing_officer,admin')
                ->name('update');

            Route::get('/{rfq:rfq_id}/pdf', [RfqController::class, 'downloadPdf'])
                ->name('pdf');

            Route::patch('/{rfq:rfq_id}/issue', [RfqController::class, 'issue'])
                ->middleware('role:purchasing_officer,admin')
                ->name('issue');

            Route::patch('/{rfq:rfq_id}/cancel', [RfqController::class, 'cancelRfq'])
                ->middleware('role:admin')
                ->name('cancel');
        });

    Route::get('/products', function () {
        $products = \App\Models\Product::with('bomLines.rawMaterial', 'bomLines.unit')->get();
        return view('master-data.products', compact('products'));
    })->name('products.index');

    Route::get('/raw-materials', function () {
        $rawMaterials = \App\Models\RawMaterial::with('baseUnit', 'vendorMaterials.vendor')->get();
        return view('master-data.raw-materials', compact('rawMaterials'));
    })->name('raw-materials.index');

    Route::get('/vendors', function () {
        $vendors = \App\Models\Vendor::with('vendorMaterials.rawMaterial')->get();
        // dd($vendors);
        return view('master-data.vendors', compact('vendors'));
    })->name('vendors.index');
});

require __DIR__.'/auth.php';
