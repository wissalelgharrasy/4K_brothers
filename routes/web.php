<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FamilleController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\MarqueController;
use App\Http\Controllers\Admin\PieceController;
use App\Http\Controllers\Admin\VehiculeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Client)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/recherche', [CatalogController::class, 'search'])->name('catalog.search');

// Cart
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/{itemKey}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{itemKey}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/panier/vider', [CartController::class, 'clear'])->name('cart.clear');

// Checkout (WhatsApp)
Route::get('/commande', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commande', [CheckoutController::class, 'process'])->name('checkout.process');

// Fiche pièce
Route::get('/piece/{id}-{slug?}', [CatalogController::class, 'piece'])
    ->where('id', '[0-9]+')
    ->name('catalog.piece');

/*
|--------------------------------------------------------------------------
| Admin & User Profile Routes (Breeze)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Panel
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // CRUDs
        Route::resource('marques', MarqueController::class);
        Route::resource('vehicules', VehiculeController::class);
        Route::resource('familles', FamilleController::class);

        // Pieces & Toggle Active
        Route::match(['patch', 'post'], 'pieces/{piece}/toggle-active', [PieceController::class, 'toggleActive'])->name('pieces.toggle-active');
        Route::resource('pieces', PieceController::class);

        // Export Catalogue
        Route::get('exports', [ImportExportController::class, 'exportsIndex'])->name('exports.index');
        Route::get('catalogue/builder', [ImportExportController::class, 'builder'])->name('catalogue.builder');
        Route::get('imports/template', [ImportExportController::class, 'downloadTemplate'])->name('imports.template');
        Route::get('imports/{import}/errors-csv', [ImportExportController::class, 'downloadErrors'])->name('imports.errors-csv');
        Route::get('export/pdf', [ImportExportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('export', [ImportExportController::class, 'export'])->name('export');
        Route::resource('imports', ImportExportController::class)->only(['index', 'create', 'store', 'show']);
    });
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Catalog wildcard routes (must be at the absolute end)
|--------------------------------------------------------------------------
*/
Route::get('/{marque}', [CatalogController::class, 'marque'])->name('catalog.marque');
Route::get('/{marque}/{vehicule}', [CatalogController::class, 'vehicule'])->name('catalog.vehicule');
