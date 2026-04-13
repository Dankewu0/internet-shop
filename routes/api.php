<?php

use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function (): void {
    require __DIR__ . "/main/user.php";
    require __DIR__ . "/main/category.php";
    require __DIR__ . "/main/product.php";
    require __DIR__ . "/main/cart.php";
    require __DIR__ . "/main/order.php";
});
