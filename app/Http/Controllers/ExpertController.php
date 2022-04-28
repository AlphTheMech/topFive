<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetAllExpertResource;
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
    public function getAllExpert()
    {
        $expert = GetAllExpertResource::collection(User::with('roles')
            ->with('personalData')
            ->with('testPermission')
            ->whereHas('roles', function ($query) {
                $query->where('roles.id', 2);
            })->paginate(10));
        return response()->json([
            'data' => [
                'items' => $expert,
                'paginate' => [
                    'total' => $expert->total(),
                    'per_page' => $expert->perPage(),
                    'current_page' => $expert->currentPage(),
                    'last_page' => $expert->lastPage(),
                    'from' => $expert->firstItem(),
                    'to' => $expert->lastItem(),
                    'count' => $expert->count(),
                    'total_pages' => $expert->lastPage()
                ],
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
