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
use Illuminate\Support\Str;

class StatisticController extends Controller
{

    /**
     * teacherExperts
     *
     * @return void
     */
    public function teacherExperts()
    {
        $statistic = TeacherExpertsResource::collection(
            TeacherExpert::with('expert')
                ->with('personalDataExpert')
                ->with('emailExpert')
                ->where('teacher_id', auth('sanctum')->user()->id)
                ->paginate(10)
        );
        return response()->json([
            'data' => [
                'items' => collect($statistic)
                    ->sortByDesc('statistics_score')
                    ->values()
                    ->all() ?? null,
                'paginate' => [
                    'total' => $statistic->total(),
                    'per_page' => $statistic->lastPage() != $statistic->currentPage()  ? $statistic->currentPage() + 1 : $statistic->currentPage(),
                    'current_page' => $statistic->currentPage(),
                    'last_page' => $statistic->lastPage(),
                    'from' => $statistic->firstItem(),
                    'to' => $statistic->lastItem(),
                    'count' => $statistic->count(),
                    'total_pages' => $statistic->lastPage()
                ],
                'code' => 200,
                'message' => 'Держи солнышко'
            ]
        ], 200);
    }

    /**
     * gettingTestStatisticsAll
     *
     * @param  mixed $request
     * @return void
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
                    'abbreviation' => Str::upper(mb_substr($personalData->last_name ?? null, 0, 1)) .
                        mb_substr($personalData->last_name ?? null, 1) . ' ' .
                        Str::upper(mb_substr($personalData->first_name ?? null, 0, 1)) . '.' .
                        Str::upper(mb_substr($personalData->middle_name ?? null, 0, 1)) . '.',
                    'email' => $user->email,
                    'statistics_score' => $stat,
                ];
            }
            $p++;
        }
        return response()->json([
            'data' => [
                'items' => collect($users)->sortByDesc('statistics_score')->values()->all() ?? null,
                'code' => 200,
                'message' => 'Держи солнышко'
            ]
        ], 200);
    }

    /**
     * gettingTestStatistics
     *
     * @param  mixed $request
     * @return void
     */
    public function gettingTestStatistics(GettingTestStatisticsRequest $request)
    {
        $statistic = GettingTestStatisticsResource::collection(
            ExpertStatistics::where('test_id', $request->test_id)
                ->paginate(10)
        );
        return response()->json([
            'data' => [
                'items' => collect($statistic)
                    ->sortByDesc('statistics_score')->values()->all() ?? null,
                'paginate' => [
                    'total' => $statistic->total(),
                    'per_page' => $statistic->lastPage() != $statistic->currentPage()  ? $statistic->currentPage() + 1 : $statistic->currentPage(),
                    'current_page' => $statistic->currentPage(),
                    'last_page' => $statistic->lastPage(),
                    'from' => $statistic->firstItem(),
                    'to' => $statistic->lastItem(),
                    'count' => $statistic->count(),
                    'total_pages' => $statistic->lastPage()
                ],
                'code' => 200,
                'message' => 'Держи солнышко'
            ]
        ], 200);
    }
}
