<?php

namespace App\Http\Controllers;

use App\Http\Requests\GettingTestStatisticsRequest;
use App\Models\ExpertStatistics;
use App\Models\PersonalData;
use App\Models\SubjectOfStudies;
use App\Models\SubjectTests;
use App\Models\TeacherExpert;
use App\Models\Tests;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
       /**
     * teacherExperts
     *
     * @return JsonResponse
     */
    public function teacherExperts()
    {
        $id = auth('sanctum')->user()->id;
        $teacherExpert = TeacherExpert::where('teacher_id', $id)->get();
        $count = count($teacherExpert);
        for ($i = 0; $i < $count; $i++) {
            $expertUser[$i] = ExpertStatistics::where('expert_id', $teacherExpert[$i]['expert_id'])->first();
        }
        $expertUserCount = count($expertUser);
        for ($i = 0; $i < $expertUserCount; $i++) {
            $attempt =  ExpertStatistics::where('expert_id', $expertUser[$i]['expert_id'])->first();
            $personalData = PersonalData::where('user_id', $expertUser[$i]['expert_id'])->first();
            $user = User::where('id', $expertUser[$i]['expert_id'])->first();
            $test_name = Tests::where('id', $expertUser[$i]['test_id'])->first();
            $subject = SubjectOfStudies::where('id', SubjectTests::where('tests_id',  $expertUser[$i]['test_id'])->first()->subject_of_studies_id)->first();
            $expertAttempt[$i] = [
                'statistics_score' => $attempt->statistics_score,
                'first_name' => $personalData->first_name,
                'middle_name' => $personalData->middle_name,
                'last_name' => $personalData->last_name,
                'email' => $user->email,
                'test_name' => $test_name->name_test,
                'subject' => $subject->name,
            ];
        }
        return response()->json([
            'data' => [
                'items' => $expertAttempt,
                'code' => 201,
                'message' => 'Держи солнышко'
            ]
        ], 201);
    }
        /**
     * gettingTestStatisticsAll
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function gettingTestStatisticsAll(Request $request)
    {
        $statistics = ExpertStatistics::get()->groupBy('expert_id');
        $p = 0;
        foreach ($statistics as $key => $statistic) {
            $personalData = PersonalData::where('user_id', $key)->first() ?? null;
            $user = User::where('id', $key)->first() ?? null;
            $stat = 0;
            foreach ($statistic as $item) {
                $stat += $item['statistics_score'];
                $users[$p] = [
                    'first_name' => $personalData->first_name,
                    'middle_name' => $personalData->middle_name,
                    'last_name' => $personalData->last_name,
                    'email' => $user->email,
                    'statistics_score' => $stat,
                ];
            }
            $p++;
        }
        return response()->json([
            'data' => [
                'items' => $user = collect($users)->sortByDesc('statistics_score')->values()->all() ?? null,
                'code' => 201,
                'message' => 'Держи солнышко'
            ]
        ], 201);
    }
        /**
     * gettingTestStatistics
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function gettingTestStatistics(GettingTestStatisticsRequest $request)
    {
        $statistics =  ExpertStatistics::where('test_id', $request->test_id)->get()->sortByDesc('statistics_score');

        $count = count($statistics);
        for ($i = 0; $i < $count; $i++) {
            $personalData = PersonalData::where('user_id', $statistics[$i]['expert_id'])->first();
            $user = User::where('id', $statistics[$i]['expert_id'])->first();
            $users[$i] = [
                'first_name' => $personalData->first_name,
                'middle_name' => $personalData->middle_name,
                'last_name' => $personalData->last_name,
                'email' => $user->email,
                'statistics_score' => $statistics[$i]['statistics_score']
            ];
        }
        return response()->json([
            'data' => [
                'items' => $user = collect($users)->sortByDesc('statistics_score')->values()->all() ?? null,
                'code' => 201,
                'message' => 'Держи солнышко'
            ]
        ], 201);
    }
}
