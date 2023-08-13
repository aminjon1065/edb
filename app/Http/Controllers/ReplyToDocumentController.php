<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ReplyToDocument;
use App\Models\ToRais;
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
                $folder = date('d-m-Y');
                $file->storeAs('public/documents/' . auth()->user()->region . '/' . $folder, $filename);
                $document->file()->create([
                    'name' => $filename,
                    'size' => round($file->getSize() / 1024 / 1024 * 1024, 2),
                    'extension' => $file->getClientOriginalExtension(),
                    'folder' => $folder,
                    'document_id' => $document->id,
                    'user_id' => auth()->user()->id
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

    public function fromRaisToUsers(Request $request, $id)
    {
        $replyRais = ToRais::whereId($id)->firstOrFail();
        if ($replyRais) {
            $newReplyTo = $request->input('replyTo', []); // Предполагается, что 'replyTo' передаётся в виде массива в запросе
            // Если replyTo равно null, инициализируем его пустым массивом
            $existingReplyTo = $replyRais->replyTo ?? [];
            // Обновляем поле replyTo в модели
            $updatedReplyTo = array_merge($existingReplyTo, $newReplyTo);
            // Альтернативный вариант с использованием оператора распространения (spread operator)
            // $updatedReplyTo = [...$existingReplyTo, ...$newReplyTo];
            $replyRais->replyTo = $updatedReplyTo;
            $replyRais->save();
            // Другие операции или перенаправления, если нужно
        } else {
            // Обработка случая, когда запись не найдена
        }
    }
}
