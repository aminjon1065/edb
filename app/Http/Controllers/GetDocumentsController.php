<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class GetDocumentsController extends Controller
{
    public function getDocuments(Request $request)
    {
        $start = $request->input('start'); // Получение даты начала месяца из запроса
        $end = $request->input('end');     // Получение даты конца месяца из запроса
        $documents = Document::where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [$start, $end]) // Фильтрация документов по дате
            ->with(['file'])
            ->get();

        return response()->json($documents);
    }

    public function report(Request $request)
    {
        $start = $request->input('start'); // Получение даты начала месяца из запроса
        $end = $request->input('end');     // Получение даты конца месяца из запроса
        $documents = Document::where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [$start, $end]) // Фильтрация документов по дате
            ->with(['file'])
            ->get();

        // Group by code and then map to get title and count for each group
        $grouped = $documents->groupBy('code')
            ->map(function ($group) {
                return [
                    'type_tj' => $group->first()->type_tj,
                    'type_ru' => $group->first()->type_ru,
                    'count' => $group->count()
                ];
            });

        return response()->json($grouped);
    }

}
