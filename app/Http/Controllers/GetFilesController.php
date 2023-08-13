<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class GetFilesController extends Controller
{
    public function getFails (){
        $files = File::where('user_id', auth()->user()->id)->with('user')->paginate(20);
        return response()->json($files);
    }
}
