<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "order_id" => ["nullable", "integer", "exists:orders,id"],
            "product_id" => ["required", "integer", "exists:products,id"],
            "quantity" => ["required", "integer", "min:1"],
            "price" => ["required", "numeric", "min:0"],
        ];
    }
}
