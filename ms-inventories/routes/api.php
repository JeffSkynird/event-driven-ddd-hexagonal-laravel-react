<?php

use App\Inventories\UI\Http\Controllers\InventoryController;
use App\Kitchen\UI\Http\Controllers\KitchenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/ingredients', [InventoryController::class, 'getInventoryPaginated']);
