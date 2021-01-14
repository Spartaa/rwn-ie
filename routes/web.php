<?php

use App\Http\Controllers\ImportExportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('file-import-export', [ImportExportController::class, 'fileImportExport'])->name('file-import-export');
Route::get('product-file-import-export', [ImportExportController::class, 'productFileImportExport'])->name('product-file-import-export');
Route::post('file-import-customer', [ImportExportController::class, 'customerFileImport'])->name('customer-file-import');
Route::get('file-export-customer', [ImportExportController::class, 'fileExport'])->name('file-export-customer');
Route::post('file-import-product', [ImportExportController::class, 'productFileImport'])->name('product-file-import');
Route::get('file-export-product', [ImportExportController::class, 'fileExportProduct'])->name('file-export-product');
