<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Services\CartItemService;

class CartItemController extends Controller
{
    public function __construct(protected CartItemService $service) {}

    public function store(Request $request)
    {
        $item = $this->service->addItem($request->all());
        return response()->json($item, 201);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $item = $this->service->updateItem($cartItem, $request->all());
        return response()->json($item, 200);
    }

    public function destroy(CartItem $cartItem)
    {
        $this->service->deleteItem($cartItem);
        return response()->json(null, 204);
    }
}
