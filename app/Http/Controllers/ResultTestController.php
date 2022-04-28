<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResultsRequest;
use App\Http\Resources\GetResultResource;
use App\Http\Resources\PaginateCollection;
use App\Http\Resources\PaginateResource;
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
            ->paginate(10));
        return response()->json([
            'items' => collect($result)->sortByDesc('mark')->values()->all() ?? null,
            'paginate' => [
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem(),
                'count' => $result->count(),
                'total_pages' => $result->lastPage()
            ],
            'code' => 200,
            'message' => 'Данные об оценке успешно получены'
        ], 200);
    }
}
