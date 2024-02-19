<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user.
     * 
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bedankt voor het registreren! U kunt nu inloggen.'
        ], 200);
    }

    /**
     * Log in a user.
     * 
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();

        if (!$user) {
           return response()->json([
                'success' => false,
                'message' => 'Geen gebruiker gevonden met dit e-mailadres.'
           ], 401);
        }

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->accessToken;
            return response()->json([
                'success' => true,
                'token' => $token,
                'message' => 'U bent succesvol ingelogd.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ongeldige inloggegevens.'
        ], 401);
    }
}
