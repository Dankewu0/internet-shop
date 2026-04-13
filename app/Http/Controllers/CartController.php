<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $service) {}

    public function index(Request $request)
    {
        $cart = $this->service->getCurrentCart($request);

        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "product_id" => ["required", "integer", "exists:products,id"],
            "quantity" => ["required", "integer", "min:1"],
            "session_id" => ["nullable", "string", "max:255"],
        ]);

        $cart = $this->service->addItem($request, (int) $data["product_id"], (int) $data["quantity"]);

        return response()->json($cart, 201);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            "items" => ["required", "array", "min:1"],
            "items.*.product_id" => ["required", "integer", "exists:products,id"],
            "items.*.quantity" => ["required", "integer", "min:0"],
            "session_id" => ["nullable", "string", "max:255"],
        ]);

        $cart = $this->service->updateItems($request, $data["items"]);

        return response()->json($cart);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            "product_id" => ["nullable", "integer", "exists:products,id"],
            "product_ids" => ["nullable", "array"],
            "product_ids.*" => ["integer", "exists:products,id"],
            "session_id" => ["nullable", "string", "max:255"],
        ]);

        $ids = [];

        if (!empty($data["product_id"])) {
            $ids[] = (int) $data["product_id"];
        }

        if (!empty($data["product_ids"])) {
            $ids = array_merge($ids, $data["product_ids"]);
        }

        if (empty($ids)) {
            $this->service->clear($request);
        } else {
            $this->service->removeItems($request, $ids);
        }

        return response()->json($this->service->getCurrentCart($request));
    }
}
