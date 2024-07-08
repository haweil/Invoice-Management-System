<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoiceAttachmentsController;
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
Route::get('/section/{id}',[InvoicesController::class,'getProducts']);
Route::resource('sections',SectionsController::class);
Route::resource('products',ProductsController::class);
Route::resource('Archive', InvoiceArchiveController::class);
Route::get('edit_invoice/{id}', [InvoicesController::class, 'edit'])->name('edit_invoice');
Route::get('Status_show/{id}', [InvoicesController::class, 'Status_show'])->name('Status_show');
Route::post('Status_Update/{id}', [InvoicesController::class, 'Status_Update'])->name('Status_Update');
Route::get('InvoicesDetails/{id}', [InvoicesDetailsController::class, 'show'])->name('InvoicesDetails');
Route::get('invoices_paid', [InvoicesController::class, 'invoices_paid'])->name('invoices_paid');
Route::get('invoices_unpaid', [InvoicesController::class, 'invoices_unpaid'])->name('invoices_unpaid');
Route::get('invoices_partial', [InvoicesController::class, 'invoices_partial'])->name('invoices_partial');
Route::get('Download_file/{invoiceNumber}/{fileName}', [InvoicesDetailsController::class, 'download_file'])->name('Download_file');
Route::get('View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'Open_file'])->name('View_file');
Route::post('Delete_file', [InvoicesDetailsController::class, 'Delete_file'])->name('Delete_file');
Route::post('InvoiceAttachments', [InvoiceAttachmentsController::class,'store'])->name('InvoiceAttachments');
Route::get('Print_invoice/{id}', [InvoicesController::class, 'Print_invoice'])->name('Print_invoice');
Route::get('/{page}', [AdminController::class, 'index']);
