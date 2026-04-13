<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "cart_id" => ["nullable", "integer", "exists:carts,id"],
            "product_id" => ["required", "integer", "exists:products,id"],
            "quantity" => ["required", "integer", "min:1"],
        ];
    }
}
