<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PersonalData;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SubjectOfStudies;
use App\Models\SubjectTests;
use App\Models\Tests;
use App\Models\TestsPermissions;
use Illuminate\Http\JsonResponse;
use App\Models\ResultTests;
use App\Models\UsersPermissions;
use App\Models\UsersRoles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class UserController extends Controller
{


    public function createSubject(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
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
    public function getInfoUser()
    {
        $id = auth('sanctum')->user()->id;
        return response()->json([
            'data' => [

                'user' => User::where('id', $id)->first(),
                'personal_data' => PersonalData::where('user_id', $id)->first(),
                'code' => 200,
                'sex' => 'Attack helicopter',
                'message' => 'Полученные данные'
            ]
        ], 200);
    }
    public function getExperts(Request $request)
    {
    }
    public function findForAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'data' => [
                'item' => [
                    'user_info' => $user,
                    'personal_data' => PersonalData::where('user_id', $user->id)->first()
                ],
                'code' => 200,
                'message' => "Держи солнышко "
            ]
        ], 200);
    }
    public function postTests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'name_test' => ['required', 'unique:tests', 'string', 'max:255'],
            'json_data' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
        $tests = Tests::create([
            'name_test' => $request->name_test,
            'json_data' => $request->json_data,
        ]);
        SubjectTests::create([
            'tests_id' => $tests->id,
            'subject_id' => SubjectOfStudies::where('name', $request->name)->first()->id,
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о тесте обновлена"
            ]
        ], 201);
    }
    public function getAllTests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
        $subjectTestId = SubjectTests::where('subject_id', SubjectOfStudies::where('name', $request->name)->first()->id)->get();
        $subjectCount = $subjectTestId->count();
        for ($i = 0; $i < $subjectCount; $i++) {
            $tests[$i] = [
                'name_test' => Tests::where('id', $subjectTestId[$i]["tests_id"])->first()->name_test,
                'json_data' => Tests::where('id', $subjectTestId[$i]["tests_id"])->first()->json_data
            ];
        }
        return response()->json([
            'data' => [
                'item' => $tests,
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }

    public function searchForAnExpert(Request $request)
    {
        $user = auth('sanctum')->user()->id;
        // $user = 4;
        // $validator = Validator::make($request->all(), [
        //     // 'tests_id' => ['required', 'integer'],
        //     'id' => ['required', 'integer'],
        // ]);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => [
        //             'code' => 422,
        //             'errors' => $validator->errors(),
        //             'message' => 'Ошибка валидации'
        //         ]
        //     ], 422);
        // }
        $test = TestsPermissions::where('user_id', $user)->get();
        $count = count($test);
        for ($i = 0; $i < $count; $i++) {
            $tests[$i] = [
                'name_test' => Tests::where('id', $test[$i]['tests_id'])->first()->name_test,
                'json_data' => Tests::where('id', $test[$i]['tests_id'])->first()->json_data
            ];
        }
        return response()->json([
            'data' => [
                "item" => $tests,
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }
    public function createTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
        UsersRoles::where('user_id', $request->id)->update([
            'role_id' => 3
        ]);

        UsersPermissions::where('user_id', $request->id)->update([
            'permission_id' => 3
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о роли успешна обновлена"
            ]
        ], 201);
    }
    public function createExpert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
        UsersRoles::where('user_id', $request->id)->update([
            'role_id' => 2
        ]);

        UsersPermissions::where('user_id', $request->id)->update([
            'permission_id' => 2
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о роли успешна обновлена"
            ]
        ], 201);
    }
    public function addingAccessToTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_subject' => ['required', 'string', 'max:255'],
            'name_test' => ['required', 'string', 'max:255'],
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
        TestsPermissions::create([
            'user_id' => $request->id,
            'tests_id' => Tests::where('name_test', $request->name_test)->first()->id
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о доступе к тесту успешна обновлена"
            ]
        ], 201);
    }
    public function postResultTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mark' => ['required'],
            'test_id' => ['required'],
            'subject_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Ошибка валидации'
                ]
            ], 422);
        }
        ResultTests::create([
            'mark' => $request->mark,
            'test_id' => $request->test_id,
            'subject_id' => $request->subject_id,
            'user_id' => auth('sanctum')->user()->id
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация об оценке успешно обновлена"
            ]
        ], 201);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]
            ], 422);
        }
        if (!Auth::attempt($request->all())) {
            return response()->json([
                'error' => [
                    'code' => 401,
                    "message" => 'This user not register'
                ]
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $user->update([
            'token' => $token
        ]);
        $cookie = cookie('jwt', $token, 60 * 24 * 3);
        return response()->json([
            'data' => [

                'role' => Role::where('id', UsersRoles::where('user_id', $user->id)
                    ->first()->role_id)
                    ->first()->slug,
                'permission' => Permission::where('id', UsersPermissions::where('user_id', $user->id)
                    ->first()->permission_id)
                    ->first()->slug,
                'code' => 200,
                'message' => "Аутентифицирован",
                'token' => $token,
            ]
        ])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function getAllExpert(Request $request)
    {
        $role = UsersRoles::where('role_id', 2)->get();
        $userRole = count($role);
        for ($i = 0; $i < $userRole; $i++) {

            $users[$i] = [
                'user' => User::where('id', $role[$i]['user_id'])->first(),
                'personal_data' => PersonalData::where('user_id', $role[$i]['user_id'])->first()
            ];
        }
        return response()->json([
            'data' => [
                $users,
                'code' => 200,
                'message' => 'Держи солнышко'
            ]
        ], 200);
    }
}