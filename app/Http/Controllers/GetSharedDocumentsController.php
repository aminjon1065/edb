<?php

namespace App\Http\Controllers;

use App\Models\ShareDocument;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetSharedDocumentsController extends Controller
{
    public function inbox(Request $request)
    {
        return $this->getMails($request, 'to');
    }

    public function sent(Request $request)
    {
        return $this->getMails($request, 'from');
    }

    private function getMails(Request $request, $field)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userId = auth()->id();
        $query = ShareDocument::where($field, $userId)
            ->with(['toUser', 'fromUser', 'document'])
            ->when($request->input('query'), function ($query, $searchQuery) {
                return $query->where(function ($subQuery) use ($searchQuery) {
                    $subQuery->whereHas('document.user', function ($subSubQuery) use ($searchQuery) {
                        $subSubQuery->where('title', 'LIKE', '%' . $searchQuery . '%')
                            ->orWhere('content', 'LIKE', '%' . $searchQuery . '%');
                    })->orWhereHas('fromUser', function ($subSubQuery) use ($searchQuery) {
                        $subSubQuery->where('full_name', 'LIKE', '%' . $searchQuery . '%');
                    })->orWhereHas('toUser', function ($subSubQuery) use ($searchQuery) {
                        $subSubQuery->where('full_name', 'LIKE', '%' . $searchQuery . '%');
                    });
                });
            })
            ->when($request->input('startDate') && $request->input('endDate'), function ($query) use ($request) {
                $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
                $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($request->input('order') && $request->input('column'), function ($query) use ($request) {
                return $query->orderBy($request->input('column'), $request->input('order'));
            })
            ->when($request->input('isControl') !== null, function ($query) use ($request) {
                $isControl = filter_var($request->input('isControl'), FILTER_VALIDATE_BOOLEAN);
                return $query->whereHas('document', function ($subQuery) use ($isControl) {
                    $subQuery->where('control', $isControl);
                });
            })
            ->when($request->input('type'), function ($query, $type) {
                return $query->whereHas('document', function ($subQuery) use ($type) {
                    $subQuery->where('code', $type);
                });
            });

        $documents = $query->paginate(10);
        return response()->json($documents);
    }

    public function showMail($uuid)
    {
        $shareDocument = ShareDocument::whereUuid($uuid)->with(['document.replyToDocument', 'fromUser', 'document.toRais.user'])->first();

        if ($shareDocument) {
            // Получаем идентификаторы пользователей из поля replyTo
            $replyToUserIds = $shareDocument->document->toRais->replyTo ?? [];

            // Инициализируем пустой массив для хранения связанных пользователей
            $replyToUsers = [];

            // Перебираем идентификаторы пользователей и ищем их в модели User
            foreach ($replyToUserIds as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $replyToCollection = collect($shareDocument->document->toRais->replyTo);
                    $idDocument = $shareDocument->document->id;
                    $sharedFindDocument = ShareDocument::where('document_id', $idDocument)->where('to', $user->id)->exists();
                    $userHasSentDocument = $replyToCollection->contains($user->id);
                    $user->hasSentDocument = $sharedFindDocument;
                    $replyToUsers[] = $user;
                }
            }

            // Добавляем массив связанных пользователей в объект $shareDocument
            $shareDocument->replyToUsers = $replyToUsers;

            // Вернуть ShareDocument с связанными пользователями в виде JSON-ответа
            return response()->json($shareDocument);
        } else {
            // Обработка случая, когда запись не найдена
            return response()->json(['message' => 'Документ не найден'], 404);
        }
    }

    public function showed($uuid)
    {
        ShareDocument::where('uuid', $uuid)->update(['opened' => true]);
    }

    public function getUnreadCount()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userId = auth()->id();
        $unreadCount = ShareDocument::where('to', $userId)
            ->where('opened', false)
            ->count();

        return response()->json(['unreadCount' => $unreadCount]);
    }

}
