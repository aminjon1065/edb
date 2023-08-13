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
        if ($request->input('toRais')) {
            $document->toRais()->create([
                'uuid' => Str::uuid()->toString(),
                'management_id' => 4,
                'document_id' => $document->id,
                'opened' => false
            ]);
            $document->shareDocument()->update([
                'isReply' => true
            ]);
        }
        if ($request->has('to')) {
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
        }


        if (!$document->shareDocument) {
            return response()->json('Ошибка при отправке', 200);
        }
        return response()->json($document->shareDocument, 201);
    }

    public function toRaisReplyDocument(Request $request, $uuid)
    {
        $shared = ShareDocument::whereUuid($uuid)->firstOrFail();
        if ($shared) {
            $shared->update(['toRais' => true]);
            $shared->document->toRais()->create([
                'uuid' => Str::uuid()->toString(),
                'management_id' => $request->input('management_id'),
                'document_id' => $shared->document->id,
            ]);
//            $raisId = User::where('email', 'rais@admin.com')->firstOrFail()->id;
            NotificationSharedMail::dispatch($shared->uuid, 3);
        }
        return response()->json('error');
    }

    public function sharedRaisReplyToUsers(Request $request, $uuid)
    {
        $document = Document::whereUuid($uuid)->firstOrFail();
        $arrTo = $document->toRais->replyTo;

        foreach ($arrTo as $item) {
            $mailUUID = Str::uuid()->toString(); // Сохраняем UUID в переменную
            $document->shareDocument()->create([
                'uuid' => $mailUUID,
                'to' => $item,
                'from' => auth()->user()->id,
                'opened' => false,
                'document_id' => $document->id,
                'toRais' => $request->input('toRais'),
                'isReply' => true
            ]);
            NotificationSharedMail::dispatch($mailUUID, $item); // Передаем UUID в метод dispatch()
        }
        return response()->json($document);
    }
}
