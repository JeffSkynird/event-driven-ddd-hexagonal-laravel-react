<?php

use App\Kitchen\UI\Http\Controllers\KitchenController;
use App\Purchases\UI\Http\Controllers\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/purchases', [PurchaseController::class, 'getAllPurchases']);