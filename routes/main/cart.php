<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::get("/cart", [CartController::class, "index"]);
Route::post("/cart/items", [CartController::class, "store"]);
Route::put("/cart/items", [CartController::class, "update"]);
Route::delete("/cart/items", [CartController::class, "destroy"]);
