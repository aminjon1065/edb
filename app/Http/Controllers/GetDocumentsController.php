<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ShareDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $start = $request->input('start');
        $end = $request->input('end');
        $field = $request->input('field');
        $shared = ShareDocument::where($field, auth()->user()->id)
            ->whereBetween('created_at', [$start, $end])
            ->with(['document'])
            ->get();

        $grouped = $shared->groupBy(function ($date) {
            return $date->document->code;
        })->map(function ($group) {
            return [
                'type_tj' => $group->first()->document->type_tj,
                'type_ru' => $group->first()->document->type_ru,
                'count' => $group->count()
            ];
        });
        // Преобразование коллекции в массив
        return response()->json($grouped->values()->all());
    }

    public function pdfReports(Request $request, $lang)
    {
        $start = \Carbon\Carbon::parse($request->input("start"));
        $end = \Carbon\Carbon::parse($request->input("end"));
        $filed = $request->input("filed");
        $filedToBlade = ''; // Объявление переменной
        if ($lang === 'ru' && $filed === 'from') {
            $filedToBlade = 'Исходящие';
        } elseif ($lang === 'ru' && $filed === 'to') {
            $filedToBlade = 'Входящие';
        } elseif ($lang === 'tj' && $filed === 'from') {
            $filedToBlade = 'Содиротӣ';
        } elseif ($lang === 'tj' && $filed === 'to') {
            $filedToBlade = 'Воридотӣ';
        }

        $shared = ShareDocument::where($filed, auth()->user()->id)
            ->whereBetween('created_at', [$start, $end])
            ->with(['document'])
            ->get();
        $grouped = $shared->groupBy(function ($date) {
            return $date->document->code;
        })->map(function ($group) use ($lang) {
            $data = [
                'count' => $group->count()
            ];
            if ($lang == 'ru') {
                $data['type_ru'] = $group->first()->document->type_ru;
            } else {
                $data['type_tj'] = $group->first()->document->type_tj;
            }
            return $data;
        });

        $pdf = PDF::loadView('pdf.invoice', compact('grouped', 'lang', 'start', 'end', 'filedToBlade'));
        return $pdf->download('report.pdf');
    }


}
