<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return response()->json('Usuário criado com sucesso', 201);
    }

    public function login(Request $request)
    {
        $credenciais = $request->only('username', 'password');
        if(!$token = Auth::guard('api')->attempt($credenciais)){
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }
        return $this->respostaToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        auth()->invalidate(true);
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }


    protected function respostaToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
