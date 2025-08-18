<?php

use App\Kitchen\UI\Http\Controllers\KitchenController;
use Illuminate\Support\Facades\Route;

Route::post('/kitchen/prepare', [KitchenController::class, 'prepareDish']);
Route::get('/kitchen/recipes', [KitchenController::class, 'getRecipesPaginated']);
Route::get('/kitchen/orders/paginate', [KitchenController::class, 'getOrdersPaginated']);
Route::get('/kitchen/orders', [KitchenController::class, 'getOrders']);