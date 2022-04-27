<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResultsRequest;
use App\Http\Resources\GetResultResource;
use App\Models\ResultTests;
use Illuminate\Http\Request;

class ResultTestController extends Controller
{
    /**
     * getResults
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function getResults(GetResultsRequest $request)
    {
        $result = GetResultResource::collection(ResultTests::with('testResult')
            ->with('userResult')
            ->with('subjectResult')
            ->where('tests_id', $request->id)
            ->get());
        return response()->json([
            'items' =>  $test = collect($result)->sortByDesc('mark')->values()->all() ?? null,
            'code' => 200,
            'message' => 'Данные об оценке успешно получены'
        ], 200);
    }
}
