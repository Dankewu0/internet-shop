<?php

namespace App\Services;

use App\Models\CartItem;

class CartItemService
{
    public function addItem(array $data)
    {
        return CartItem::create($data);
    }

    public function updateItem(CartItem $cartItem, array $data)
    {
        $cartItem->update($data);
        return $cartItem;
    }

    public function deleteItem(CartItem $cartItem)
    {
        $cartItem->delete();
    }
}
