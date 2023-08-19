<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ShareDocument;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $field = $request->input('field');
        $shared = ShareDocument::where($field, auth()->user()->id)
            ->whereBetween('created_at', [$start, $end])
            ->with(['document'])
            ->get();

        $grouped = $shared->groupBy(function ($date) {
            return $date->document->code; // grouping by document's code
        })->map(function ($group) {
            return [
                'type_tj' => $group->first()->document->type_tj, // Adjust based on your model structure
                'type_ru' => $group->first()->document->type_ru, // Adjust based on your model structure
                'count' => $group->count()
            ];
        });
        return response()->json($grouped->values()->all());
    }

    public function pdfReports($lang)
    {
        $documents = Document::where('user_id', auth()->user()->id)
            ->with(['file'])
            ->get();
        $pdf = PDF::loadView('pdf.invoice', compact('documents'));
        return $pdf->download('report.pdf');
    }
}
