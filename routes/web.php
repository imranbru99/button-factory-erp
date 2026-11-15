<?php

use App\Http\Controllers\PublicSite\SiteController;
use App\Models\Delivery;
use App\Models\Inspection;
use App\Models\Invoice;
use App\Services\Documents\FactoryPdfService;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::get('/catalog', [SiteController::class, 'catalog'])->name('catalog');
Route::get('/catalog/{slug}', [SiteController::class, 'product'])->name('product');
Route::post('/inquiries', [SiteController::class, 'inquire'])->name('inquiries.store');
Route::get('/locale/{locale}', [SiteController::class, 'locale'])->name('locale');

Route::middleware('auth')->group(function () {
    Route::get('/documents/invoices/{invoice}', fn (Invoice $invoice, FactoryPdfService $pdf) => $pdf->invoice($invoice))->name('documents.invoice');
    Route::get('/documents/packing-lists/{delivery}', fn (Delivery $delivery, FactoryPdfService $pdf) => $pdf->packingList($delivery))->name('documents.packing');
    Route::get('/documents/certificates/{inspection}', fn (Inspection $inspection, FactoryPdfService $pdf) => $pdf->certificate($inspection))->name('documents.certificate');
});
