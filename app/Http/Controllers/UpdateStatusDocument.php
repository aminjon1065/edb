<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class UpdateStatusDocument extends Controller
{
    public function updateStatus($uuid): void
    {
        Document::where('uuid', $uuid)->update(['status' => 'success']);
    }
    public function updateControl(Request $request,$uuid):void
    {
        Document::whereUuid($uuid)->update(['control'=>1, 'date_done'=>$request->input('date_done')]);
    }
}
