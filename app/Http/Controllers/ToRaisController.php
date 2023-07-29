<?php

namespace App\Http\Controllers;

use App\Models\ToRais;
use Illuminate\Http\Request;

class ToRaisController extends Controller
{
    public function getRepliedToRais()
    {
        return ToRais::with('document.user')->paginate(25);
    }

    public function getRepliedToRaisById($id)
    {
        return ToRais::whereId($id)->with(['document.file', 'document.user'])->firstOrFail();
    }
}
