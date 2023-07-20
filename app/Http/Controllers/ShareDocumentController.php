<?php

namespace App\Http\Controllers;

use App\Events\NotificationSharedMail;
use App\Models\Document;
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
                $file->storeAs('public/documents/' . auth()->user()->region, $filename);
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

        if (!$document->shareDocument) {
            return response()->json('Ошибка при отправке', 200);
        }

        return response()->json($document->shareDocument, 201);
    }
}
