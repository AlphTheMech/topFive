<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\AllSubjectResource;
use App\Http\Resources\GetSubjectResource;
use App\Models\ResultTests;
use App\Models\SubjectOfStudies;
use App\Models\SubjectTests;
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
                'message' => "Информация о предмете успешно добавлена"
            ]
        ], 201);
    }
    /**
     * updateSubject
     *
     * @param  mixed $subject
     * @param  mixed $request
     * @return void
     */
    public function updateSubject(SubjectOfStudies $subject, UpdateSubjectRequest $request)
    {
        $subject->update([
            'name' => $request->name
        ]);
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => "Информация о предмете обновлена"
            ]
        ], 200);
    }

    /**
     * deleteSubject
     *
     * @param  mixed $subject
     * @return void
     */
    public function deleteSubject(SubjectOfStudies $subject)
    {
        $subject->subjectStudy ? SubjectTests::where('subject_of_studies_id', $subject->id)->delete() ?? null : null;
        $subject->subjectResult ? ResultTests::where('subject_id', $subject->id)->delete() ?? null : null;
        SubjectOfStudies::where('id', $subject->id)->delete();
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => "Информация о предмете успешно удалена"
            ]
        ], 200);
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
