<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class GetDocumentsController extends Controller
{
    public function getDocuments()
    {
        $documents = Document::where('user_id', auth()->user()->id)->with(['file', 'replyToDocument', 'shareDocument.toUser'])->get();
        return response()->json($documents);
    }
}
