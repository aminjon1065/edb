<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Register extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'region' => 'required|string',
            'position' => 'required|string',
            'department' => 'required|string',
            'rank' => 'required|string',
            'signature' => 'image',
            'avatar' => 'image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->toArray(),
            ], 422);
        }

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'password',
            'position',
            'rank',
            'department',
            'region',
            'permission',
            'role',
        ]);

        $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];
        $data['password'] = Hash::make($data['password']);
        $data['uuid'] = Str::uuid()->toString();

        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $imagePath = 'public/avatars';
            $image->storeAs($imagePath, $imageName);
            $data['avatar'] = $imageName;
        }

        $user = User::create($data);
        event(new Registered($user));
        
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Успешно зарегистрировано',
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Непредвиденная ошибка'
        ], 422);
    }
}
