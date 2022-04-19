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
use App\Models\ExpertStatistics;
use App\Models\ExpertUser;
use App\Models\TeacherExpert;
use App\Events\BlockEvent;
use App\Events\MsgReadEvent;
use App\Events\PrivateChatEvent;
use App\Events\SessionEvent;
use App\Http\Requests\AddingAccessToTestRequest;
use App\Http\Requests\CreateSubjectRequest;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\FindForAdminRequest;
use App\Http\Requests\GettingTestStatisticsRequest;
use App\Http\Requests\PostResultTestRequest;
use App\Http\Requests\PostTestsRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UserResource;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Claims\Subject;

class UserController extends Controller
{
    /**
     * createSubject
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function createSubject(CreateSubjectRequest $request): JsonResponse
    {
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

    /**
     * getInfoUser
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function getInfoUser(Request $request): JsonResponse
    {
      return  $request->user()->all();
        // $id = auth('sanctum')->user()->id;
        $cookie = $request->cookie('jwt');
        return response()->json([
            'data' => [
                'user' => InfoResource::make(User::where('token', $cookie)->first()),
                'token' => User::where('token', $cookie)->first()->token,
                'email_verified' => User::where('token', $cookie)->first()->email_verified_at,
                'male' => 'Attack helicopter',
            ],
            'code' => 200,
            'message' => 'Полученные данные'
        ], 200);
    }
    /**
     * findForAdmin
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function findForAdmin(FindForAdminRequest $request): JsonResponse
    {
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
    /**
     * postTests
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function postTests(PostTestsRequest $request): JsonResponse
    {
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
    /**
     * getAllTests
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function getAllTests(Request $request): JsonResponse
    {
        $subjectTestId = SubjectTests::where('subject_id', SubjectOfStudies::where('name', $request->name)->first()->id)->get();
        $subjectCount = $subjectTestId->count();
        for ($i = 0; $i < $subjectCount; $i++) {
            $test = Tests::where('id', $subjectTestId[$i]["tests_id"])->first();
            $explode = explode('@', $test->name_test);
            if (array_key_exists(1, $explode)) {
                $tests[$i] = [
                    'test_id' => $test->id,
                    'subject_id' => SubjectOfStudies::where('name', $request->name)->first()->id,
                    'name_test' => $explode[0],
                    'author' => $explode[1],
                    'full_name_test' => $test->name_test,
                    'json_data' => $test->json_data
                ];
            } else {
                $tests[$i] = [
                    'test_id' => $test->id,
                    'subject_id' => SubjectOfStudies::where('name', $request->name)->first()->id,
                    'name_test' => $test->name_test,
                    'json_data' => $test->json_data
                ];
            }
        }
        return response()->json([
            'data' => [
                'items' => $tests,
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }

    /**
     * searchForAnExpert
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function searchForAnExpert(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user()->id;
        $test = TestsPermissions::where('user_id', $user)->get();
        $count = count($test);
        for ($i = 0; $i < $count; $i++) {
            $tests_collection = Tests::where('id', $test[$i]['tests_id'])->first();

            $explode = explode('@', $tests_collection->name_test);
            if (array_key_exists(1, $explode)) {
                $tests[$i] = [
                    'id' => $tests_collection->id,
                    'name_test' => $explode[0],
                    'author' => $explode[1],
                    'full_name_test' => $tests_collection->name_test,
                    'json_data' => $tests_collection->json_data
                ];
            } else {
                $tests[$i] = [
                    'id' => $tests_collection->id,
                    'name_test' => $tests_collection->name_test,
                    'json_data' => $tests_collection->json_data
                ];
            }
        }

        return response()->json([
            'data' => [
                "items" => $tests,
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }
    /**
     * createTeacher
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function createTeacher(CreateTeacherRequest $request): JsonResponse
    {
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
    /**
     * addingAccessToTest
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function addingAccessToTest(AddingAccessToTestRequest $request): JsonResponse
    {
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
    public function postResultTest(PostResultTestRequest $request): JsonResponse
    {
        $user = auth('sanctum')->user()->id;
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
    /**
     * login
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
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

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    /**
     * getAllExpert
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function getAllExpert(Request $request): JsonResponse
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
                $subject_collection = SubjectOfStudies::where('id', SubjectTests::where('tests_id', $testAll[$j]['tests_id'])->first()->subject_id)->first();
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
     * getResults
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function getResults(Request $request): JsonResponse
    {
        $result = ResultTests::where('test_id', $request->id)->get();
        $count = count($result);
        for ($i = 0; $i < $count; $i++) {
            $tests[$i] = [
                'user' => User::where('id', $result[$i]['user_id'])->first(),
                'subject' => SubjectOfStudies::where('id', $result[$i]['subject_id'])->first()->name,
                'name_test' => Tests::where('id', $result[$i]['test_id'])->first()->name_test,
                // 'json_data' => Tests::where('id', $result[$i]['test_id'])->first()->json_data
                'mark' => ResultTests::where('test_id', $result[$i]['test_id'])->first()->mark
            ];
        }

        return response()->json([
            'items' =>  $test = collect($tests)->sortByDesc('mark')->values()->all() ?? null,
            'code' => 200,
            'message' => 'Данные об оценке успешно получены'
        ], 200);
    }
    /**
     * gettingTestStatistics
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function gettingTestStatistics(GettingTestStatisticsRequest $request): JsonResponse
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
    /**
     * gettingTestStatisticsAll
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function gettingTestStatisticsAll(Request $request): JsonResponse
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
     * teacherExperts
     *
     * @return JsonResponse
     */
    public function teacherExperts(): JsonResponse
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
            $subject = SubjectOfStudies::where('id', SubjectTests::where('tests_id',  $expertUser[$i]['test_id'])->first()->subject_id)->first();
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
     * blockUser
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function blockUser(Session $session): JsonResponse
    {
        $session->block();
        broadcast(new BlockEvent($session->id, true));
        return response()->json(null, 201);
    }

    /**
     * unblockUser
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function unblockUser(Session $session): JsonResponse
    {
        $session->unblock();
        broadcast(new BlockEvent($session->id, false));
        return response()->json(null, 201);
    }
    /**
     * send
     *
     * @param  mixed $session
     * @param  mixed $request
     * @return JsonResponse
     */
    public function send(Session $session, Request $request): JsonResponse
    {
        //Отправлять friend_id ,message. Хранить id чата
        $message = $session->messages()->create([
            'content' => $request->message
        ]);
        $chat = $message->createForSend($session->id);
        $message->createForReceive($session->id, $request->to_user);
        broadcast(new PrivateChatEvent($message->content, $chat));
        return response()->json($chat->id, 200);
    }

    /**
     * chats
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function chats(Session $session): JsonResponse
    {
        return response()->json(ChatResource::collection($session->chats->where('user_id', auth('sanctum')->user()->id)));
    }

    /**
     * readMessage
     *
     * @param  mixed $session
     * @return void
     */
    public function readMessage(Session $session)
    {
        $chats = $session->chats->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth('sanctum')->user()->id);
        foreach ($chats as $chat) {
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new MsgReadEvent(new ChatResource($chat), $chat->session_id));
        }
    }

    /**
     * clearMessages
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function clearMessages(Session $session): JsonResponse
    {
        $session->deleteChats();
        $session->chats->count() == 0 ? $session->deleteMessages() : '';
        return response()->json('cleared', 200);
    }
    /**
     * createSession
     *
     * @param  mixed $request
     * @return void
     */
    public function createSession(Request $request)
    {
        $session = Session::create(['user1_id' => auth('sanctum')->user()->id, 'user2_id' => $request->friend_id]);
        $modifiedSession = new SessionResource($session);
        broadcast(new SessionEvent($modifiedSession, auth('sanctum')->user()->id));
        return response()->json($modifiedSession, 200);
    }
    /**
     * getFriends
     *
     * @return JsonResponse
     */
    public function getFriends(): JsonResponse
    {
        return response()->json(UserResource::collection(User::where('id', '!=', auth()->id())->get()), 200);
    }
    /**
     * getSubject
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function getSubject(Request $request): JsonResponse
    {
        $subject = SubjectTests::get();
        $count = count($subject);
        for ($i = 0; $i < $count; $i++) {
            $items[$i] = [
                'subject_name' => SubjectOfStudies::where('id', $subject[$i]['subject_id'])->first()->name,
                'name_test' => Tests::where('id', $subject[$i]['tests_id'])->first()->name_test,
                'subject_id' => $subject[$i]['subject_id'],
                'test_id' => $subject[$i]['tests_id']
            ];
        }
        return response()->json([
            'data' => [
                'items' => $items,
                'code' => 200,
                'message' => 'Держи солнышко'
            ]
        ], 200);
    }
    /**
     * allSubject
     *
     * @return JsonResponse
     */
    public function allSubject(): JsonResponse
    {
        return response()->json([
            'items' => SubjectOfStudies::get(),
            'code' => 200,
            'message' => 'Держи солнышко'
        ], 200);
    }
}
