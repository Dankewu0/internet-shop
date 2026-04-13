<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartItemController;

Route::post("/cart/items", [CartItemController::class, "store"]);
Route::put("/cart/items/{cartItem}", [CartItemController::class, "update"]);
Route::delete("/cart/items/{cartItem}", [CartItemController::class, "destroy"]);
