<?php

namespace App\Http\Controllers;

use App\Models\ToRais;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ToRaisController extends Controller
{
    public function getRepliedToRais(Request $request)
    {

        $query = ToRais::with('document')
            ->with('document.user')
            ->when($request->input('query'), function ($query, $searchQuery){
                return $query->where(function ($subQuery)  use ($searchQuery){
                    $subQuery->whereHas('document.user', function ($subSubQuery) use ($searchQuery){
                        $subSubQuery->where('title', 'LIKE', '%' . $searchQuery . '%')
                            ->orWhere('content', 'LIKE', '%' . $searchQuery . '%')
                            ->orWhere('type', 'LIKE', '%' . $searchQuery . '%');                    });
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

    public function getRepliedToRaisById($id)
    {
        return ToRais::whereId($id)->with(['document.file', 'document.user'])->firstOrFail();
    }
}
