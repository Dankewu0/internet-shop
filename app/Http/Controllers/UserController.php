<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "email", "max:255", "unique:users,email"],
            "phone" => ["nullable", "string", "max:30"],
            "password" => ["required", "string", "min:6", "max:255"],
        ]);

        $payload = $this->service->register($data);

        return response()->json($payload, 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required", "string"],
        ]);

        return response()->json($this->service->login($data["email"], $data["password"]));
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $this->service->logout($request->user());

        return response()->json(["message" => "Logged out"]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            "name" => ["sometimes", "string", "max:255"],
            "email" => [
                "sometimes",
                "email",
                "max:255",
                Rule::unique("users", "email")->ignore($user->id),
            ],
            "phone" => ["sometimes", "nullable", "string", "max:30"],
            "password" => ["sometimes", "string", "min:6", "max:255"],
        ]);

        if (!empty($data["password"])) {
            $data["password"] = bcrypt($data["password"]);
        }

        $user->update($data);

        return response()->json($user->refresh());
    }
}
