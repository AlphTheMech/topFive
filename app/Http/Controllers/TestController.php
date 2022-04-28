<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddingAccessToTestRequest;
use App\Http\Requests\GetAllTestsRequest;
use App\Http\Requests\PostResultTestRequest;
use App\Http\Requests\PostTestsRequest;
use App\Http\Resources\GetAllCollection;
use App\Http\Resources\GetAllResource;
use App\Models\ExpertStatistics;
use App\Models\ExpertUser;
use App\Models\ResultTests;
use App\Models\Role;
use App\Models\SubjectOfStudies;
use App\Models\SubjectTests;
use App\Models\TeacherExpert;
use App\Models\Tests;
use App\Models\TestsPermissions;
use App\Models\UsersRoles;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * postTests
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function postTests(PostTestsRequest $request)
    {
        $tests = Tests::create([
            'name_test' => $request->name_test,
            'json_data' => $request->json_data,
        ]);
        SubjectTests::create([
            'tests_id' => $tests->id,
            'subject_of_studies_id' => SubjectOfStudies::where('name', $request->name)->first()->id,
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о тесте обновлена"
            ]
        ], 201);
    }

    /**
     * getAllTests
     *
     * @param  mixed $request
     * @return void
     */
    public function getAllTests(GetAllTestsRequest $request)
    {
        $tests = GetAllResource::collection(Tests::with('subjectTests')->paginate(10));
        return response()->json([
            'data' => [
                'items' =>  $tests,
                'paginate' => [
                    'total' => $tests->total(),
                    'per_page' => $tests->perPage(),
                    'current_page' => $tests->currentPage(),
                    'last_page' => $tests->lastPage(),
                    'from' => $tests->firstItem(),
                    'to' => $tests->lastItem(),
                    'count' => $tests->count(),
                    'total_pages' => $tests->lastPage()
                ],
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }
    /**
     * addingAccessToTest
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function addingAccessToTest(AddingAccessToTestRequest $request)
    {
        $testid = Tests::where('name_test', $request->name_test)->first()->id;
        TestsPermissions::create([
            'user_id' => $request->id,
            'tests_id' => $testid
        ]);

        $user = auth('sanctum')->user()->id;
        $expert = auth()->user()->roles->first()->slug;
        $expertTeacher = Role::where('id', UsersRoles::where('user_id', $request->id)->first()->role_id)->first()->slug;
        if ($expert == 'expert') {
            ExpertUser::create([
                'user_id' => $request->id,
                'test_id' => $testid,
                'expert_id' => $user
            ]);
        }
        if ($expert == 'teacher' &&  $expertTeacher == 'expert') {
            $teachereee = TeacherExpert::where('teacher_id', $user)->where('expert_id', $request->id)->first() ?? null;
            $experteee = ExpertStatistics::where('expert_id', $request->id)->where('test_id', $testid)->first() ?? null;
            if (!$teachereee) {
                TeacherExpert::create([
                    'teacher_id' => $user,
                    'expert_id' => $request->id,
                ]);
            }
            if (!$experteee) {
                ExpertStatistics::create([
                    'expert_id' => $request->id,
                    'test_id' => $testid,
                    'statistics_score' => 0,
                ]);
            }
        }
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о доступе к тесту успешна обновлена"
            ]
        ], 201);
    }

    /**
     * postResultTest
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function postResultTest(PostResultTestRequest $request)
    {
        $user = auth('sanctum')->user()->id;
        $number = 1;
        $userHas = ResultTests::where('user_id', $user)->get();
        $userHasTest = $userHas->where('test_id', $request->test_id)->first();
        if ($userHas != null) {
            $userHasTest = $userHas->where('tests_id', $request->test_id)->first();
            if ($userHasTest != null) {
                $usr = ResultTests::where('user_id', $user)->get();
                $attempt = $usr->where('tests_id', $request->test_id)->first();
                if ($attempt->number_of_attempts == 2) {
                    return response()->json([
                        'error' => [
                            'code' => 403,
                            'message' => 'Number of attempts ended'
                        ]
                    ], 403);
                }
                if ($attempt->mark > $request->mark) {
                    $attempt->update([
                        'number_of_attempts' =>  $number += 1
                    ]);
                } else {
                    $attempt->update([
                        'mark' => $request->mark,
                        'number_of_attempts' =>  $number += 1
                    ]);
                }
            }
        }
        if ($userHas == null || $userHasTest == null) {
            ResultTests::create([
                'number_of_attempts' => $number,
                'mark' => $request->mark,
                'tests_id' => $request->test_id,
                'subject_id' => $request->subject_id,
                'user_id' => $user
            ]);
        }

        $testsExpert = ExpertUser::where('test_id', $request->test_id)->get();
        $expertId = $testsExpert->where('user_id', $user)->first()->expert_id;
        if ($request->mark >= 50) {
            $expertStat = ExpertStatistics::where('expert_id', $expertId)->get();
            $stat = $expertStat->where('test_id', $request->test_id)->first();
            $countStat = $stat->statistics_score;
            $stat->update([
                'statistics_score' => $countStat += 1
            ]);
        }
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация об оценке успешно обновлена"
            ]
        ], 201);
    }
}
