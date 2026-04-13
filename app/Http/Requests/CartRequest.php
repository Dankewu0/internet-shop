<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "user_id" => ["nullable", "integer", "exists:users,id"],
            "session_id" => ["nullable", "string", "max:255"],
        ];
    }
}
