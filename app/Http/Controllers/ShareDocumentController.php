<?php

namespace App\Http\Controllers;

use App\Events\NotificationSharedMail;
use App\Models\Document;
use App\Models\ShareDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareDocumentController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->has('to')) {
            return response()->json('Такого пользователя не существует', 200);
        }
        $document = Document::create([
            'uuid' => Str::uuid()->toString(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'control' => $request->input('control'),
            'status' => 'pending',
            'type' => $request->input('type'),
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

        $arrTo = $request->input('to');
        foreach ($arrTo as $item) {
            $mailUUID = Str::uuid()->toString(); // Сохраняем UUID в переменную
            $document->shareDocument()->create([
                'uuid' => $mailUUID,
                'to' => $item,
                'from' => auth()->user()->id,
                'opened' => false,
                'document_id' => $document->id,
                'toRais' => $request->input('toRais'),
                'isReply' => false
            ]);
            NotificationSharedMail::dispatch($mailUUID, $item); // Передаем UUID в метод dispatch()
        }
        if ($request->input('toRais')) {
            $document->toRais()->create([
                'document_id' => $document->id,
//                'toRais' => $request->input('toRais'),
            ]);
        }
        if (!$document->shareDocument) {
            return response()->json('Ошибка при отправке', 200);
        }
        return response()->json($document->shareDocument, 201);
    }

    public function toRaisReplyDocument($uuid)
    {
        $shared = ShareDocument::whereUuid($uuid)->firstOrFail();
        if ($shared) {
            $shared->update(['toRais' => true]);
            $shared->document->toRais()->create([
                'document_id' => $shared->document->id,
            ]);
            $raisId = User::where('email', 'rais@admin.com')->firstOrFail()->id;
            NotificationSharedMail::dispatch($shared->uuid, $raisId);
        }
        return response()->json('error');
    }
}
