<?php

namespace App\Http\Controllers;

use App\Http\Resources\SearchForAnExpertResource;
use App\Models\PersonalData;
use App\Models\SubjectOfStudies;
use App\Models\SubjectTests;
use App\Models\Tests;
use App\Models\TestsPermissions;
use App\Models\User;
use App\Models\UsersRoles;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    /**
     * getAllExpert
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function getAllExpert(Request $request)
    {
        $role = UsersRoles::where('role_id', 2)->get();
        $count = count($role);
        for ($i = 0; $i < $count; $i++) {
            $user = User::where('id', $role[$i]['user_id'])->first();
            $personal = PersonalData::where('user_id', $role[$i]['user_id'])->first();
            $testAll = TestsPermissions::where('user_id', $role[$i]['user_id'])->get();
            $countTest = count($testAll);
            for ($j = 0; $j <  $countTest; $j++) {
                $test_collection = Tests::where('id', $testAll[$j]['tests_id'])->first();
                $subject_collection = SubjectOfStudies::where('id', SubjectTests::where('tests_id', $testAll[$j]['tests_id'])->first()->subject_of_studies_id)->first();
                $aboba = explode('@', $test_collection->name_test);
                if (array_key_exists(1, $aboba)) {
                    $all[$i][$j] = [
                        'name_test' => $aboba[0],
                        'author' => $aboba[1],
                        'full_name_test' => $test_collection->name_test,
                        'test_id' => $test_collection->id,
                        'subject_id' => $subject_collection->id,
                        'subject_name' => $subject_collection->name,
                    ];
                } else {
                    $all[$i][$j] = [
                        'name_test' => $test_collection->name_test,
                        'test_id' => $test_collection->id,
                        'subject_id' => $subject_collection->id,
                        'subject_name' => $subject_collection->name,
                    ];
                }
            }

            $tests[$i] = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'personal_data' => [
                    'first_name' => $personal->first_name,
                    'last_name' => $personal->last_name,
                    'middle_name' => $personal->middle_name,
                ],
                'tests' => $all[$i]
            ];
        }
        return response()->json([
            'data' => [
                'items' => $tests,
                'code' => 200,
                'message' => 'Держи солнышко'
            ]
        ], 200);
    }
        /**
     * searchForAnExpert
     *
     * @param  mixed $request
     * @return void
     */
    public function searchForAnExpert(Request $request)
    {
        return response()->json([
            'data' => [
                "items" => SearchForAnExpertResource::collection(User::with('testPermission')
                    ->where('id', auth('sanctum')->user()->id)
                    ->first()->testPermission),
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }
}
