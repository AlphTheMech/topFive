<?php

namespace App\Http\Controllers;

use App\Http\Requests\GettingTestStatisticsRequest;
use App\Http\Resources\GettingTestStatisticsResource;
use App\Http\Resources\TeacherExpertsResource;
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
        return response()->json([
            'data' => [
                'items' => collect(TeacherExpertsResource::collection(TeacherExpert::with('expert')
                    ->with('personalDataExpert')
                    ->with('emailExpert')
                    ->get()
                    ->where('teacher_id', auth('sanctum')->user()->id)))
                    ->sortByDesc('statistics_score')
                    ->values()
                    ->all() ?? null,
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
        return response()->json([
            'data' => [
                'items' => collect(GettingTestStatisticsResource::collection(ExpertStatistics::where('test_id', $request->test_id)
                    ->get()))
                    ->sortByDesc('statistics_score')->values()->all() ?? null,
                'code' => 201,
                'message' => 'Держи солнышко'
            ]
        ], 201);
    }
}