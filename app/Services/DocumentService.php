<?php

namespace App\Services;

use App\Models\ShareDocument;
use Carbon\Carbon;

class DocumentService
{
    public function getDocuments($userId, $field, $request): array
    {
        $query = ShareDocument::where($field, $userId)
            ->with(['toUser', 'fromUser', 'document'])
            ->when($request->input('query'), function ($query, $searchQuery) {
                return $query->where(function ($subQuery) use ($searchQuery) {
                    $subQuery->whereHas('document.user', function ($subSubQuery) use ($searchQuery) {
                        $subSubQuery->where('title', 'LIKE', '%' . $searchQuery . '%')
                            ->orWhere('content', 'LIKE', '%' . $searchQuery . '%')
                            ->orWhere('type', 'LIKE', '%' . $searchQuery . '%');
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
        return $documents;
    }
}
