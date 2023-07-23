<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GetUsersListController extends Controller
{
    public function usersList()
    {
        $users = User::all()->map(function ($user) {
            if (auth()->user()->id === 1) {
                if ($user->role == 99) {
                    return [
                        'value' => $user->id,
                        'label' => $user->full_name . ' (Председатель)'
                    ];
                }
            }

            if ($user->role == 99) {
                return null; // Пропустить пользователя с указанным email
            }

            $usersLabel = $user->full_name === auth()->user()->full_name ? $user->full_name . ' (Себе)' : $user->full_name;
            return [
                'value' => $user->id,
                'label' => $usersLabel . ' (' . $user->region . ')'
            ];
        })->filter()->values(); // Удалить все значения null из списка и преобразовать в массив

        return response()->json($users, 200);

    }

}
