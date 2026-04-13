<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "slug" => ["nullable", "string", "max:255", "unique:products,slug"],
            "category_id" => ["required", "integer", "exists:categories,id"],
            "price" => ["required", "numeric", "min:0"],
            "attributes" => ["nullable", "array"],
        ];
    }
}
