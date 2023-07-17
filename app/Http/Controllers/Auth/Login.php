<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $attr['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Пользователь не найден'
            ], 401);
        }

        if (!Auth::attempt($attr)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неправильный E-mail или пароль'
            ], 401);
        }

        $token = auth()->user()->createToken('__sign_token')->plainTextToken;
        $user = $this->isAuth();
        $lastLoginController = new LogAuth();
        $lastLoginController->store();

        return response()->json([
            'status' => 'success',
            'message' => 'Успешно вошли в систему',
            'token' => $token,
            'role' => $user['role'] == 1 ? 'Admin' : ($user['role'] == 99 ? 'Rais' : 'User'),
            'user' => $user
        ], 201);
    }

    public function isAuth()
    {
        return auth()->user();
    }

    public function checkAuth()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
