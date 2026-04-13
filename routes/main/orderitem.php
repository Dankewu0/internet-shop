<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderItemController;

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/order-items", [OrderItemController::class, "store"]);
    Route::put("/order-items/{orderItem}", [
        OrderItemController::class,
        "update",
    ]);
    Route::delete("/order-items/{orderItem}", [
        OrderItemController::class,
        "destroy",
    ]);
});
