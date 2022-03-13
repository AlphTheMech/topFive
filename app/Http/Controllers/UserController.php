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
use App\Http\Resources\FindResource;
use App\Http\Resources\InfoResource;
use App\Http\Resources\PersonalResource;
use App\Models\Dialog;
use App\Models\ExpertStatistics;
use App\Models\ExpertUser;
use App\Models\Messages;
use App\Models\TeacherExpert;
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
                'user' => InfoResource::make(User::where('id', $id)->first()),
                'token' => User::where('id', $id)->first()->token,
                'email_verified' => User::where('id', $id)->first()->email_verified,
                'male' => 'Attack helicopter',
            ],
            'code' => 200,
            'message' => 'Полученные данные'
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
                'user_info' => FindResource::make($user),
                // 'personal_data' => PersonalData::where('user_id', $user->id)->first()
            ],
            // 'token' => $user->token,
            'code' => 200,
            'message' => "Держи солнышко"
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
            'email' => ['required'],
            'role' => ['required', 'string'],
            "permission" => ['required', 'string']
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
        UsersRoles::where('user_id', User::where('email', $request->email)->first()->id)->update([
            'role_id' => Role::where('slug', $request->role)->first()->id,
        ]);

        UsersPermissions::where('user_id', User::where('email', $request->email)->first()->id)->update([
            'permission_id' => Permission::where('slug', $request->permission)->first()->id,
        ]);

        $user = auth('sanctum')->user()->id;
        $teacher = Role::where('id', UsersRoles::where('user_id', $user)->first()->role_id)->first()->slug;
        if ($request->role == 'expert' && $teacher == 'teacher') {
            TeacherExpert::create([
                'teacher_id' => $user,
                'expert_id' => User::where('email', $request->email)->first()->id,
            ]);
            return response()->json([
                'data' => [
                    'code' => 201,
                    'message' => "Информация о роли успешна обновлена учитель"
                ]
            ], 201);
        }

        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о роли успешна обновлена"
            ]
        ], 201);
    }
    public function createExpert(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'token' => ['required'],
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
        // UsersRoles::where('user_id', User::where('token', $request->cookie('jwt'))->first()->id)->update([
        //     'role_id' => 2
        // ]);

        // UsersPermissions::where('user_id', User::where('token', $request->cookie('jwt'))->first()->id)->update([
        //     'permission_id' => 2
        // ]);
        // return response()->json([
        //     'data' => [
        //         'code' => 201,
        //         'message' => "Информация о роли успешна обновлена"
        //     ]
        // ], 201);
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
        $testid = Tests::where('name_test', $request->name_test)->first()->id;
        TestsPermissions::create([
            'user_id' => $request->id,
            'tests_id' => $testid
        ]);
        $user = auth('sanctum')->user()->id;
        $expert = Role::where('id', UsersRoles::where('user_id', $user)->first()->role_id)->first()->slug;
        $expertTeacher = Role::where('id', UsersRoles::where('user_id', $request->id)->first()->role_id)->first()->slug;
        if ($expert == 'expert') {
            ExpertUser::create([
                'user_id' => $request->id,
                'test_id' => $testid,
                'expert_id' => $user
            ]);
        }
        if ($expert == 'teacher' &&  $expertTeacher == 'expert') {
            ExpertStatistics::create([
                'expert_id' => $request->id,
                'test_id' => $testid,
                'statistics_score' => 0,
            ]);
        }
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Информация о доступе к тесту успешна обновлена"
            ]
        ], 201);
    }
    public function postResultTest(Request $request)
    {
        $user = auth('sanctum')->user()->id;
        $validator = Validator::make($request->all(), [
            'mark' => ['required', 'integer'],
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
        $number = 1;
        $userHas = ResultTests::where('user_id', $user)->get();
        $userHasTest = $userHas->where('test_id', $request->test_id)->first();
        if ($userHas != null) {
            $userHasTest = $userHas->where('test_id', $request->test_id)->first();
            if ($userHasTest != null) {
                $usr = ResultTests::where('user_id', $user)->get();
                $attempt = $usr->where('test_id', $request->test_id)->first();
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
                'test_id' => $request->test_id,
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
    // public function store(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'middle_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', Rules\Password::defaults()],
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => [
    //                 'code' => 422,
    //                 'errors' => $validator->errors(),
    //                 'message' => 'Ошибка валидации'
    //             ]
    //         ], 422);
    //     }
    //     $user = User::create([
    //         'name' => $request->first_name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     PersonalData::create([
    //         'first_name' => $request->first_name,
    //         'middle_name' => $request->middle_name,
    //         'last_name' => $request->last_name,
    //         'user_id' => $user->id,
    //     ]);
    //     return response()->json()->setStatusCode(204);
    // }
    public function store(Request $request)
    {
        // return response()->json([$request->all()]);

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
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

        $user = User::create([
            'name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
        $token = $user->createToken('token')->plainTextToken;
        $user->update([
            'token' => $token
        ]);
        PersonalData::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'user_id' => auth('sanctum')->user()->id
        ]);
        $user->roles()->attach(Role::where('slug', 'user')->first());
        $user->save();
        $user->permissions()->attach(Permission::where('slug', 'standard-user')->first());
        $user->save();
        // $cookie = cookie('jwt', $token, 60 * 24 * 3);
        return response()->json([
            'token' => $token,
            'code' => 201

        ], 201);
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
    public function getMessage(Request $request)
    {
        // $user= auth('sanctum')->user()->id;

        // $dialog=Dialog::where('to_id', $user)->get();
        $message = Messages::where('dialog_id', $request->id)->get();
        $dialog = Dialog::where('id', $request->id)->first();
        // return $message;
        $count = count($message);
        for ($i = 0; $i < $count; $i++) {
            if ($dialog->from_id == $message[$i]['author_id']) {
                $item[$i] = [
                    'message_from' => $message[$i]['content'],
                ];
            } else {
                $item[$i] = [
                    'message_to' => $message[$i]['content'],
                ];
            }

            // $message[$i] = [


            // 	'message_from'=>Messages::where('author_id', Dialog::where('id', $request->id)->first()->from_id)->first()->content,
            // 	'message_to'=>Messages::where('author_id', Dialog::where('id', $request->id)->first()->to_id)->first()->content,
            // 	// 'message_from' => Tests::where('id', $test[$i]['tests_id'])->first()->content,
            //  //   'message_to' => Tests::where('id', $test[$i]['tests_id'])->first()->content,
            //     // 'json_data' => Tests::where('id', $test[$i]['tests_id'])->first()->json_data
            // ];
        }
        return response()->json([
            'data' => $item,
            'code' => 201,
            'message' => 'Держи солнышко'
            // 'message_from'=>Messages::where('author_id', $user)->
        ], 201);
    }
    public function getDialog()
    {
        $user = auth('sanctum')->user()->id;
        $dialog = Dialog::where('to_id', $user)->get();
        $count = count($dialog);
        for ($i = 0; $i < $count; $i++) {
            $message[$i] = [
                'from_id' => $dialog[$i]['from_id'],
                'from' => User::where('id', $dialog[$i]['from_id'])->first(),
                'to_id' => $dialog[$i]['to_id'],
                'to' => User::where('id', $dialog[$i]['to_id'])->first(),
                // 'message_from' => Tests::where('id', $test[$i]['tests_id'])->first()->content,
                //   'message_to' => Tests::where('id', $test[$i]['tests_id'])->first()->content,
                // 'json_data' => Tests::where('id', $test[$i]['tests_id'])->first()->json_data
            ];
        }
        return response()->json([
            'data' =>  $message,
            'code' => 201,
            'message' => 'Держи солнышко'
        ], 201);

        // $count=count($dialog);
        // for ($i=0; $i < $count; $i++) { 
        //     $dialogs[$i]= 
        // }
    }
    public function gettingTestStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_id' => ['required'],
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
                'items' => $users,
                'code' => 201,
                'message' => 'Держи солнышко'
            ]
        ], 201);
    }
    public function gettingTestStatisticsAll(Request $request)
    {
        $statistics = ExpertStatistics::get()->sortByDesc('statistics_score');
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
                'items' => $users,
                'code' => 201,
                'message' => 'Держи солнышко'
            ]
        ], 201);
    }
    public function getResults(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'errors' => $validator->errors(),
                    'code' => 422,
                    'message' => 'Validation error'
                ]
            ], 422);
        }
    	$result= ResultTests::where('test_id', $request->id)->get();
        $count=count($result);
        for ($i = 0; $i < $count; $i++) {
            $tests[$i] = [
            	'user_id'=>User::where('id', $result[$i]['user_id'])->first(),
            	'subject'=>SubjectOfStudies::where('id', $result[$i]['subject_id'])->first()->name,
                'name_test' => Tests::where('id', $result[$i]['test_id'])->first()->name_test,
                // 'json_data' => Tests::where('id', $result[$i]['test_id'])->first()->json_data
                'mark'=>ResultTests::where('test_id', $result[$i]['test_id'])->first()->mark
            ];
        }
        return response()->json([
            'items'=> $tests,
            'code'=>200,
            'message'=>'Данные об оценке успешно получены'
        ],200);
    }
}
