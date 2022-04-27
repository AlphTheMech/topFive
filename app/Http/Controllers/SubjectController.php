<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectRequest;
use App\Http\Resources\AllSubjectResource;
use App\Http\Resources\GetSubjectResource;
use App\Models\SubjectOfStudies;
use App\Models\Tests;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * createSubject
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function createSubject(CreateSubjectRequest $request)
    {
        SubjectOfStudies::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о предмете обновлена"
            ]
        ], 201);
    }
    /**
     * getSubject
     *
     * @return void
     */
    public function getSubject()
    {
        return response()->json([
            'data' => [
                'items' => GetSubjectResource::collection(Tests::with('subjectTests')->get()),
                'code' => 200,
                'message' => 'Держи солнышко'
            ]
        ], 200);
    }

    /**
     * allSubject
     *
     * @return void
     */
    public function allSubject()
    {
        return response()->json([
            'items' => AllSubjectResource::collection(SubjectOfStudies::get()),
            'code' => 200,
            'message' => 'Держи солнышко'
        ], 200);
    }
}
