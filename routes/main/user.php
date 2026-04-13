<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post("/register", [UserController::class, "register"]);
Route::post("/login", [UserController::class, "login"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/me", [UserController::class, "me"]);
    Route::post("/logout", [UserController::class, "logout"]);
    Route::put("/me", [UserController::class, "update"]);
});
