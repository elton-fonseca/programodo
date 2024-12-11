<?php

use App\Http\Controllers\Error;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Success;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Webhook;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/link/{instructionId}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/cb48/{cb48}', [PaymentController::class, 'cb48'])->name('payment.cb48');

Route::get('success/{instructionId}', Success::class)->name('success');
Route::get('error/{instructionId?}', Error::class)->name('error');

Route::post('/link/process', [PaymentController::class, 'process'])->name('payment.process');
Route::post('/wallet/process', [WalletController::class, 'process'])->name('wallet.process');
Route::post('/wallet/validate-marchant', [WalletController::class, 'validateMerchant'])->name('wallet.validate_marchant');
Route::get('/webhook/process', Webhook::class)->name('webhook.process');
