<?php

namespace App\Http\Controllers;

use App\Models\ShareDocument;
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
                            ->orWhere('content', 'LIKE', '%' . $searchQuery . '%')
                            ->orWhere('type', 'LIKE', '%' . $searchQuery . '%');
                    })->orWhereHas('fromUser', function ($subSubQuery) use ($searchQuery) {
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
            });

        $documents = $query->paginate(20);

        return response()->json($documents);
    }

    public function showMail($uuid)
    {
        return ShareDocument::with(['document', 'fromUser', 'replyMail'])
            ->where('uuid', $uuid)
            ->first();
    }

    public function showed($uuid)
    {
        ShareDocument::where('uuid', $uuid)->update(['opened' => true]);
    }

}
