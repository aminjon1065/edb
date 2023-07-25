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
}
