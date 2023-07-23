<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ReplyToDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReplyToDocumentController extends Controller
{
    public function reply(Request $request, $uuid)
    {
        $replyToDocument = Document::whereUuid($uuid)->firstOrFail();
        $document = Document::create([
            'uuid' => Str::uuid()->toString(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'control' => $replyToDocument->control,
            'status' => $replyToDocument->status,
            'type' => $replyToDocument->type,
            'user_id' => auth()->user()->id,
            'date_done' => $request->input('date_done'),
        ]);
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = str_replace(' ', '_', $file->getClientOriginalName());
                $filename = auth()->user()->first_name . '_' . auth()->user()->last_name . '_' . auth()->user()->region . '_' . uniqid() . '_' . $originalName;
                $file->storeAs('public/documents/' . auth()->user()->region . '/' . $document->uuid, $filename);
                $document->file()->create([
                    'name' => $filename,
                    'size' => round($file->getSize() / 1024 / 1024 * 1024, 2),
                    'extension' => $file->getClientOriginalExtension(),
                    'document_id' => $document->id,
                ]);
            }
        }
        $replyDocument = ReplyToDocument::create([
            'to' => $replyToDocument->user_id,
            'from' => auth()->user()->id,
            'document_id' => $document->id,
            'reply_document_id' => $replyToDocument->id,
        ]);
        return response()->json($replyDocument, 200);
    }
}
