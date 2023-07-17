<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAuth as LogAuthAlias;

class LogAuth extends Controller
{
    public function store():void
    {
        LogAuthAlias::create([
            'user_id' => auth()->user()->id,
        ]);
    }
}
