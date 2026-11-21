<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\WorkerController;
use App\Http\Controllers\Admin\ManageVendorController;
use App\Http\Controllers\Auth\RegisterWorkerController;

// Halaman Utama (Redirect ke login)
Route::get('/', function () {
    return redirect()->route('login');
});

// WORKER REGISTER (Di luar auth sebab belum login)
Route::get('/register/worker', [RegisterWorkerController::class, 'create'])->name('register.worker');
Route::post('/register/worker', [RegisterWorkerController::class, 'store'])->name('register.worker.store');

// SEMUA ROUTE DI BAWAH INI WAJIB LOGIN DULU
Route::middleware(['auth'])->group(function () {

    // Pintu masuk selepas login berjaya
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----------------- ADMIN -----------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Admin approve or manage worker
        Route::get('/workers', [\App\Http\Controllers\Admin\WorkerController::class, 'index'])->name('workers.index');
        Route::get('/workers/{id}/approve', [\App\Http\Controllers\Admin\WorkerController::class, 'approve'])->name('workers.approve');
        
        // Admin Manage Vendor
        Route::get('/vendors', [ManageVendorController::class, 'index'])->name('vendors.index');
        Route::get('/vendors/{id}/approve', [ManageVendorController::class, 'approve'])->name('vendors.approve');
        Route::get('/vendors/{id}/reject', [ManageVendorController::class, 'reject'])->name('vendors.reject');

        // Untuk Buka/Tutup Slot Vendor
        Route::post('/vendors/toggle-slots', [\App\Http\Controllers\Admin\ManageVendorController::class, 'toggleSlots'])->name('vendors.toggle-slots');

        // FASA 5: Admin Settlement
        Route::get('/settlements', [\App\Http\Controllers\Admin\SettlementController::class, 'index'])->name('settlements.index');
        Route::get('/settlements/{invoiceId}/approve', [\App\Http\Controllers\Admin\SettlementController::class, 'approve'])->name('settlements.approve');

        // reporting (FASA 6)
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.export');
    });

    // ----------------- WORKER -----------------
    Route::middleware(['role:worker'])->prefix('worker')->name('worker.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Worker\DashboardController::class, 'index'])->name('dashboard');

        // page manage menu
        Route::get('/manage-menu', [\App\Http\Controllers\Worker\MenuController::class, 'index'])->name('menu.index');
        
        // Worker Stock Tally (Fasa 4)
        Route::get('/stock-tally', [\App\Http\Controllers\Worker\StockTallyController::class, 'index'])->name('stock.tally');
        Route::post('/stock-tally/verify/{invoiceId}', [\App\Http\Controllers\Worker\StockTallyController::class, 'verify'])->name('stock.verify');
        Route::get('/menu/toggle/{itemId}', [\App\Http\Controllers\Worker\StockTallyController::class, 'toggleStatus'])->name('menu.toggle');

        // FASA 5: Worker Settlement
        Route::get('/settlements', [\App\Http\Controllers\Worker\SettlementController::class, 'index'])->name('settlements.index');
        Route::post('/settlements/{invoiceId}/calculate', [\App\Http\Controllers\Worker\SettlementController::class, 'calculate'])->name('settlements.calculate');
    });

    // ----------------- VENDOR -----------------
    Route::middleware(['role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');

        // MANAGE FOODS (BAHARU)
        Route::get('/foods', [\App\Http\Controllers\Vendor\FoodController::class, 'index'])->name('foods.index');
        Route::post('/foods', [\App\Http\Controllers\Vendor\FoodController::class, 'store'])->name('foods.store');
        Route::delete('/foods/{id}', [\App\Http\Controllers\Vendor\FoodController::class, 'destroy'])->name('foods.destroy');
        
        // Vendor Invoice (Fasa 4)
        Route::get('/invoice/create', [\App\Http\Controllers\Vendor\InvoiceController::class, 'create'])->name('invoice.create');
        Route::post('/invoice/store', [\App\Http\Controllers\Vendor\InvoiceController::class, 'store'])->name('invoice.store');

        // FASA 6: Vendor Settlement & Receipt
        Route::get('/settlements', [\App\Http\Controllers\Vendor\SettlementController::class, 'index'])->name('settlements.index');
        Route::get('/settlements/{id}/download-receipt', [\App\Http\Controllers\Vendor\SettlementController::class, 'downloadReceipt'])->name('receipt.download');
    });

    // ----------------- PUBLIC -----------------
    Route::middleware(['role:public'])->prefix('public')->name('public.')->group(function () {
        Route::get('/menu', [\App\Http\Controllers\PublicArea\MenuController::class, 'index'])->name('menu');
        
        // Public Apply Vendor
        Route::get('/apply-vendor', [\App\Http\Controllers\PublicArea\VendorApplicationController::class, 'create'])->name('apply.vendor');
        Route::post('/apply-vendor', [\App\Http\Controllers\PublicArea\VendorApplicationController::class, 'store'])->name('apply.vendor.store');
    });

});