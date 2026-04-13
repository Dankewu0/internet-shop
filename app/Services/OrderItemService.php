<?php

namespace App\Services;

use App\Models\OrderItem;

class OrderItemService
{
    public function addItem(array $data)
    {
        return OrderItem::create($data);
    }

    public function updateItem(OrderItem $orderItem, array $data)
    {
        $orderItem->update($data);
        return $orderItem;
    }

    public function deleteItem(OrderItem $orderItem)
    {
        $orderItem->delete();
    }
}
