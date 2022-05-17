<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\AddingAccessToTestRequest;
use App\Http\Requests\DeleteTestRequest;
use App\Http\Requests\GetAllTestsRequest;
use App\Http\Requests\PostResultTestRequest;
use App\Http\Requests\PostTestsRequest;
use App\Http\Requests\RemoveAccessToTestRequest;
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
                'message' => "Информация о тесте успешно добавлена"
            ]
        ], 201);
    }
    /**
     * updateTests
     *
     * @param  mixed $request
     * @param  mixed $test
     * @return void
     */
    public function updateTests(Request $request, Tests $test)
    {
        $test->update([
            'json_data' => $request->json_data,
        ]);
        // SubjectTests::where('tests_id', $test->id)->update([
        //     'tests_id' => $test->id,
        //     'subject_of_studies_id' => SubjectOfStudies::where('name', $request->name)->first()->id,
        // ]);
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => "Информация о тесте успешно обновлена"
            ]
        ], 200);
    }
    /**
     * deleteTest
     *
     * @param  mixed $request
     * @return void
     */
    public function deleteTest(DeleteTestRequest $request)
    {
        SubjectTests::where('tests_id', $request->test_id)->delete();
        Tests::where('id', $request->test_id)->delete();
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => "Информация о тесте успешно удалена"
            ]
        ], 200);
    }
    /**
     * getAllTests
     *
     * @param  mixed $request
     * @return void
     */
    public function getAllTests(Request $request)
    {
        if ($request->name && $request->name_test) {
            $tests = GetAllResource::collection(Tests::with('subjectTests')->where('name_test', $request->name_test)->whereHas('subjectTests', function ($query) use ($request) {
                $query->where('name', $request->name);
            })->paginate(10));
        } else if ($request->name) {
            $tests = GetAllResource::collection(Tests::with('subjectTests')->whereHas('subjectTests', function ($query) use ($request) {
                $query->where('name', $request->name);
            })->paginate(10));
        } else {
            $tests = GetAllResource::collection(Tests::with('subjectTests')->paginate(10));
        }
        return response()->json([
            'data' => [
                'items' =>  $tests,
                'paginate' => [
                    'total' => $tests->total(), // Общее число элементов
                    'per_page' => $tests->lastPage() != $tests->currentPage()  ? $tests->currentPage() + 1 : $tests->currentPage(), // Следующая страница
                    'current_page' => $tests->currentPage(), // Текущая страница
                    'last_page' => $tests->lastPage(), // Последняя  страница
                    'from' => $tests->firstItem(), // С какого элемента
                    'to' => $tests->lastItem(), // По какой элемент
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
     * removeAccessToTest
     *
     * @param  mixed $request
     * @return void
     */
    public function removeAccessToTest(RemoveAccessToTestRequest $request)
    {
        if ($request->id != auth('sanctum')->user()->id) {
            TestsPermissions::where('tests_id', $request->test_id)->where('user_id', $request->user_id)->delete();
            return response()->json([
                'data' => [
                    'code' => 200,
                    'message' => 'Информация о доступе к тесту успешно удалена',

                ]
            ], 200);
        }
        throw new ApiException(422, 'Ты че долбаеб?', 'Removing your own access');
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
