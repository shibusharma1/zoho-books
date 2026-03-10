<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZohoController;

Route::get('/zoho/connect', [ZohoController::class, 'redirectToZoho']);
Route::get('/zoho/callback', [ZohoController::class, 'handleZohoCallback']);
Route::get('/zoho/organizations', [ZohoController::class, 'organizations']);


// Customers
Route::get('/zoho/customers', [ZohoController::class, 'customers']);
Route::get('/zoho/customer/create', [ZohoController::class, 'createCustomerForm']);
Route::post('/zoho/customer/store', [ZohoController::class, 'createCustomer']);
Route::get('/zoho/customer/{id}/edit', [ZohoController::class, 'editCustomer']);
Route::post('/zoho/customer/{id}/update', [ZohoController::class, 'updateCustomer']);
Route::get('/zoho/customer/{id}/delete', [ZohoController::class, 'deleteCustomer']);


// taxes
Route::get('/zoho/taxes', [ZohoController::class, 'taxList']);
Route::get('/zoho/taxes/create', [ZohoController::class, 'createTaxForm']);
Route::post('/zoho/taxes/store', [ZohoController::class, 'storeTax']);

Route::get('/zoho/taxes/{id}/edit', [ZohoController::class, 'editTaxForm']);
Route::put('/zoho/taxes/{id}', [ZohoController::class, 'updateTax']);

Route::delete('/zoho/taxes/{id}', [ZohoController::class, 'deleteTax']);


Route::get('/', function () {
    return view('welcome');
});
