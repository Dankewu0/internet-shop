<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Services\OrderItemService;

class OrderItemController extends Controller
{
    public function __construct(protected OrderItemService $service) {}

    public function store(Request $request)
    {
        $item = $this->service->addItem($request->all());
        return response()->json($item, 201);
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $item = $this->service->updateItem($orderItem, $request->all());
        return response()->json($item, 200);
    }

    public function destroy(OrderItem $orderItem)
    {
        $this->service->deleteItem($orderItem);
        return response()->json(null, 204);
    }
}
