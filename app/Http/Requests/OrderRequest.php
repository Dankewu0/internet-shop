<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "user_id" => ["nullable", "integer", "exists:users,id"],
            "email" => ["nullable", "email", "max:255"],
            "phone" => ["required", "string", "max:30"],
            "total_price" => ["required", "numeric", "min:0"],
        ];
    }
}
