<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::post("/orders", [OrderController::class, "store"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/orders", [OrderController::class, "index"]);
});
