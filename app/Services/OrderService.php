<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private readonly CartService $cartService) {}

    public function getUserOrders(int $userId)
    {
        return Order::query()
            ->with("items.product")
            ->where("user_id", $userId)
            ->latest()
            ->get();
    }

    public function createFromCart(Request $request, array $data): Order
    {
        $cart = $this->cartService->resolveCart($request)->load("items.product");

        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                "cart" => "Cart is empty.",
            ]);
        }

        $user = $request->user();
        $email = $user?->email ?? ($data["email"] ?? null);
        $phone = $user?->phone ?? ($data["phone"] ?? null);

        if (blank($email) || blank($phone)) {
            throw ValidationException::withMessages([
                "email" => "Email is required.",
                "phone" => "Phone is required.",
            ]);
        }

        return DB::transaction(function () use ($cart, $user, $email, $phone): Order {
            $totalPrice = (float) $cart->items->sum(
                fn($item) => $item->quantity * (float) $item->product->price
            );

            $order = Order::query()->create([
                "user_id" => $user?->id,
                "email" => $email,
                "phone" => $phone,
                "total_price" => $totalPrice,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    "product_id" => $item->product_id,
                    "quantity" => $item->quantity,
                    "price" => $item->product->price,
                ]);
            }

            $cart->items()->delete();

            return $order->load("items.product");
        });
    }
}
