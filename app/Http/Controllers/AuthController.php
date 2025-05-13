<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    public function login(Request $request) {

        try{
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string'
            ], [
                'email.required' => 'El correo electronico es requerido',
                'email.email' => 'El correo electronico debe ser una direccion de correo valida',
                'password.required' => 'La contraseña es requerida'
            ]);

            $credentials = $request->only('email', 'password');

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json(['message' => 'Datos incorrectos'], 401);
            }

            $token = Auth::attempt($credentials);

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No tiene permisos'
                ], 401);
            }

            $user = Auth::user();
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'token_type' => 'bearer',
                ]
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error de validación', 'errors' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al iniciar sesión', 'error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser un texto',
            'email.required' => 'El correo electronico es requerido',
            'email.email' => 'El correo electronico debe ser una direccion de correo valida',
            'password.required' => 'La contraseña es requerida'
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario creado correctamente',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'token_type' => 'bearer',
            ]
        ]);
    }
    public function logout() {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Cierre de sesion exitoso'
        ]);
    }

}
