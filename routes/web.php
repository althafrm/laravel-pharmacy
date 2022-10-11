<?php

use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::group([
        'prefix' => 'user',
        'middleware' => 'can:users-only',
        'as' => 'user.',
    ], function () {
        Route::get('/prescription', [UserController::class, 'prescriptionIndex'])->name('prescription.index');
        Route::get('/prescription/create', [UserController::class, 'createPrescription'])->name('prescription.create');
        Route::post('/prescription/store', [UserController::class, 'storePrescription'])->name('prescription.store');
        Route::get('/prescription/{id}/show', [UserController::class, 'showPrescription'])->name('prescription.show');
        Route::post('/quotation/{quotation_id}/confirm-status/approved', [UserController::class, 'confirmQuotationApproved'])
            ->name('quotation.confirm-status.approved');
        Route::post('/quotation/{quotation_id}/confirm-status/rejected', [UserController::class, 'confirmQuotationRejected'])
            ->name('quotation.confirm-status.rejected');
    });

    Route::group([
        'prefix' => 'pharmacy',
        'middleware' => 'can:pharmacy-only',
        'as' => 'pharmacy.',
    ], function () {
        Route::get('/prescription', [PharmacyController::class, 'prescriptionIndex'])->name('prescription.index');
        Route::get('/prescription/{prescription_id}/show', [PharmacyController::class, 'showPrescription'])
            ->name('prescription.show');
        Route::get('/quotation/create/{prescription_id}', [PharmacyController::class, 'createQuotation'])
            ->name('prescription.create-quotation');
        Route::post('/quotation/store/{prescription_id}', [PharmacyController::class, 'storeQuotation'])
            ->name('quotation.store');
        Route::get('/drug/search', [PharmacyController::class, 'searchDrug']);
        Route::post(
            '/quotation/{quotation_id}/confirm-status/delivered',
            [PharmacyController::class, 'confirmQuotationDelivered']
        )->name('quotation.confirm-status.delivered');
    });
});
