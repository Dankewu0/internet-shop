<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getCurrentCart(Request $request): Cart
    {
        return $this->resolveCart($request)->load("items.product");
    }

    public function addItem(Request $request, int $productId, int $quantity): Cart
    {
        $cart = $this->resolveCart($request);
        Product::query()->findOrFail($productId);

        $item = $cart->items()->firstOrNew(["product_id" => $productId]);
        $item->quantity = (int) ($item->quantity ?? 0) + $quantity;
        $item->save();

        return $cart->fresh()->load("items.product");
    }

    public function updateItems(Request $request, array $items): Cart
    {
        $cart = $this->resolveCart($request);

        foreach ($items as $payload) {
            $productId = (int) $payload["product_id"];
            $quantity = (int) $payload["quantity"];
            Product::query()->findOrFail($productId);

            if ($quantity <= 0) {
                $cart->items()->where("product_id", $productId)->delete();
                continue;
            }

            $item = $cart->items()->firstOrNew(["product_id" => $productId]);
            $item->quantity = $quantity;
            $item->save();
        }

        return $cart->fresh()->load("items.product");
    }

    public function removeItems(Request $request, array $productIds): Cart
    {
        $cart = $this->resolveCart($request);
        $ids = collect($productIds)->map(fn($id) => (int) $id)->filter()->unique();

        if ($ids->isNotEmpty()) {
            $cart->items()->whereIn("product_id", $ids->all())->delete();
        }

        return $cart->fresh()->load("items.product");
    }

    public function clear(Request $request): void
    {
        $cart = $this->resolveCart($request);
        $cart->items()->delete();
    }

    public function resolveCart(Request $request): Cart
    {
        $userId = $request->user()?->id;

        if ($userId !== null) {
            return Cart::query()->firstOrCreate(["user_id" => $userId], ["session_id" => null]);
        }

        $sessionId = $this->resolveSessionId($request);

        return Cart::query()->firstOrCreate(["session_id" => $sessionId], ["user_id" => null]);
    }

    public function resolveSessionId(Request $request): string
    {
        $sessionId = (string) (
            $request->header("X-Session-Id")
            ?? $request->query("session_id")
            ?? $request->input("session_id")
            ?? ""
        );

        if ($sessionId === "") {
            $sessionId = (string) Str::uuid();
        }

        if (mb_strlen($sessionId) > 255) {
            throw ValidationException::withMessages([
                "session_id" => "Session id is too long.",
            ]);
        }

        return $sessionId;
    }
}
