<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function delete($uuid): void
    {
        $user = auth()->user();
        if ($user->role !== 1) {
            abort(403);
        }
        $document = Document::where('uuid', $uuid)->firstOrFail();
        $document->file()->delete();
        $document->shareDocument()->delete();
        $document->replyToDocument()->delete();
        $document->toRais()->delete();
        $document->delete();
    }
}
