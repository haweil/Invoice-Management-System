<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\InvoicesDetailsController;
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::resource('invoices', InvoicesController::class);
Route::resource('sections',SectionsController::class);
Route::resource('products',ProductsController::class);
Route::get('/section/{id}',[InvoicesController::class,'getProducts']);
Route::get('InvoicesDetails/{id}', [InvoicesDetailsController::class, 'show'])->name('InvoicesDetails');
Route::get('Download_file/{invoiceNumber}/{fileName}', [InvoicesDetailsController::class, 'download_file'])->name('Download_file');
Route::get('View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'Open_file'])->name('View_file');
Route::post('Delete_file', [InvoicesDetailsController::class, 'Delete_file'])->name('Delete_file');
Route::get('/{page}', [AdminController::class, 'index']);