<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get("/products", [ProductController::class, "index"]);
Route::get("/products/{slug}", [ProductController::class, "show"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/products", [ProductController::class, "store"]);
    Route::put("/products/{product}", [ProductController::class, "update"]);
    Route::delete("/products/{product}", [ProductController::class, "destroy"]);
});
