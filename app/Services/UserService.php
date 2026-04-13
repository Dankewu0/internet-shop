<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function register(array $data): array
    {
        $user = User::query()->create([
            "name" => $data["name"],
            "email" => $data["email"],
            "phone" => $data["phone"] ?? null,
            "password" => Hash::make($data["password"]),
        ]);

        return [
            "user" => $user,
            "token" => $user->createToken("api")->plainTextToken,
        ];
    }

    public function login(string $email, string $password): array
    {
        $user = User::query()->where("email", $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                "email" => "Invalid credentials.",
            ]);
        }

        return [
            "user" => $user,
            "token" => $user->createToken("api")->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
