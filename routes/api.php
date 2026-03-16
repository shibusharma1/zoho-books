<?php
use App\Http\Controllers\WebhookReceiverController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook', [WebhookReceiverController::class, 'receive']);
