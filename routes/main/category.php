<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get("/categories", [CategoryController::class, "index"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/categories", [CategoryController::class, "store"]);
    Route::put("/categories/{category}", [CategoryController::class, "update"]);
    Route::delete("/categories/{category}", [CategoryController::class, "destroy"]);
});
