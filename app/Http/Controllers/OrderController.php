<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service) {}

    public function index(Request $request)
    {
        return response()->json($this->service->getUserOrders((int) $request->user()->id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "email" => ["nullable", "email", "max:255"],
            "phone" => ["nullable", "string", "max:30"],
            "session_id" => ["nullable", "string", "max:255"],
        ]);

        return response()->json($this->service->createFromCart($request, $data), 201);
    }
}
