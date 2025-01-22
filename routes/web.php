<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchivedInvoiceController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
// Auth::routes(['register' => false]);
Route::middleware('auth')->group(function () {


    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('invoices/export/', [InvoiceController::class, 'export']);

    Route::resource('invoices', InvoiceController::class);

    Route::resource('sections', SectionController::class);

    Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class);

    Route::resource('archived-invoices', ArchivedInvoiceController::class);

    Route::resource('roles', RoleController::class);
    
    Route::resource('users', UserController::class);

    Route::post('change-status/{id}', [InvoiceController::class, 'changeStatus'])->name('change-status');

    Route::get('paid-invoices', [InvoiceController::class, 'paidInvoices']);

    Route::get('unpaid-invoices', [InvoiceController::class, 'unpaidInvoices']);

    Route::get('partially-paid-invoices', [InvoiceController::class, 'partiallyPaidInvoices']);

    Route::get('section/{id}', [InvoiceController::class, 'getProducts']);

    Route::get('edit-invoice/{id}', [InvoiceController::class, 'edit']);

    Route::get('print-invoice/{id}', [InvoiceController::class, 'printInvoice']);

    Route::get('invoices-details/{id}', [InvoicesDetailsController::class, 'edit']);

    Route::get('view-file/{invoice_number}/{filename}', [InvoicesDetailsController::class, 'open_file']);

    Route::get('download-file/{invoice_number}/{filename}', [InvoicesDetailsController::class, 'download_file']);

    Route::post('delete-file', [InvoicesDetailsController::class, 'delete_file'])->name('delete_file');

    Route::resource('products', ProductController::class);

    Route::get('/{page}', [AdminController::class, 'index']);


});
