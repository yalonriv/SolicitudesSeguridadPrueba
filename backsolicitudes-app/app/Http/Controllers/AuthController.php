<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *     title="APP para gestión de solicitudes de seguridad",
 *     description="Endpoints relacionados con la autenticación de usuarios",
 *     version="1.0"
 * )
 */
class AuthController extends Controller
{
    /**
     * Iniciar sesión y obtener un token de acceso.
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="Autenticación de usuario",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5c...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Credenciales incorrectas")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            Log::warning('Intento de inicio de sesión fallido', ['email' => $credentials['email']]);

            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    /**
     * Obtener el usuario autenticado.
     *
     * @OA\Get(
     *     path="/api/user",
     *     summary="Obtener información del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuario autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function user(Request $request)
    {
        Log::info('Usuario autenticado obtenido', ['user_id' => $request->user()->id]);
        return response()->json($request->user());
    }

    /**
     * Cerrar sesión y revocar el token de acceso.
     *
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sesión cerrada")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        Log::info('Usuario cerró sesión', ['user_id' => $request->user()->id]);
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
