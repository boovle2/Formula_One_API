<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function guestToken(Request $request): JsonResponse
    {
        $guestUser = User::firstOrCreate(
            ['email' => 'guest@formula1.local'],
            [
                'name' => 'Guest User',
                'password' => Hash::make((string) str()->uuid()),
            ]
        );

        $token = $guestUser->createToken(
            'guest-token',
            ['read'],
            now()->addHours(12)
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'abilities' => ['read'],
            'user' => [
                'id' => $guestUser->id,
                'name' => $guestUser->name,
                'email' => $guestUser->email,
            ],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $token = $user->createToken(
            'user-token',
            ['read', 'crud'],
            now()->addDay()
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'abilities' => ['read', 'crud'],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['De ingevoerde gegevens zijn ongeldig.'],
            ]);
        }

        $token = $user->createToken(
            'user-token',
            ['read', 'crud'],
            now()->addDay()
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'abilities' => ['read', 'crud'],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'abilities' => $request->user()->currentAccessToken()?->abilities ?? [],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(status: 204);
    }
}
