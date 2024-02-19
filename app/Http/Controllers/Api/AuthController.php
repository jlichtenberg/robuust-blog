<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     title="Authentication API",
 *     version="1.0.0",
 *     description="API documentation about the authentication endpoints.",
 * )
 */

class AuthController extends Controller
{
    /**
     * Register a new user.
     * 
     * @param RegisterRequest $request
     * @return JsonResponse
     * 
     * 
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Registers a new user with the provided name, email, and password.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User information",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="The name of the user"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="The email address of the user"),
     *             @OA\Property(property="password", type="string", format="password", example="password", description="The password of the user")
     *         )
     *     ),
     *     @OA\Response(response="200", description="User registered successfully"),
     *     @OA\Response(response="400", description="Bad request. Indicates that the request body is invalid or incomplete."),
     *     @OA\Response(response="422", description="Unprocessable entity. Indicates that the request body is invalid or incomplete."),
     *     @OA\Response(response="500", description="Internal server error. Indicates a server-side problem."),
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="Accept header",
     *         @OA\Schema(type="string", default="application/json")
     *     )
     * )
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
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user",
     *     description="Authenticate a user with the provided email and password.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="The email address of the user"),
     *             @OA\Property(property="password", type="string", format="password", example="password", description="The password of the user")
     *         )
     *     ),
     *     @OA\Response(response="200", description="User authenticated successfully"),
     *     @OA\Response(response="401", description="Unauthorized. Indicates invalid credentials or user not found."),
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="Accept header",
     *         @OA\Schema(type="string", default="application/json")
     *     )
     * )
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
