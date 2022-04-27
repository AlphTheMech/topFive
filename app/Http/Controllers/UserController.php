<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\UsersPermissions;
use App\Models\UsersRoles;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\FindResource;
use App\Http\Resources\InfoResource;
use App\Models\TeacherExpert;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\FindForAdminRequest;


class UserController extends Controller
{
    /**
     * getInfoUser
     *
     * @param  mixed $request
     * @return void
     */
    public function getInfoUser(Request $request)
    {
        return response()->json([
            'data' => [
                'items' =>  InfoResource::make(User::with('personalData')->where('token', $request->cookie('jwt'))->first())
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
    public function findForAdmin(FindForAdminRequest $request)
    {
        return response()->json([
            'data' => [
                'user_info' => FindResource::make(User::where('email', $request->email)->first()),
            ],
            'code' => 200,
            'message' => "Держи солнышко"
        ], 200);
    }
    /**
     * createTeacher
     *
     * @param  mixed $request
     * @return void
     */
    public function createTeacher(CreateTeacherRequest $request)
    {
        $user = auth('sanctum')->user()->id;
        $user_request = User::where('email', $request->email)->first()->id;
        UsersRoles::where('user_id', $user_request)->update([
            'role_id' => Role::where('slug', $request->role)->first()->id,
        ]);

        UsersPermissions::where('user_id', $user_request)->update([
            'permission_id' => Permission::where('slug', $request->permission)->first()->id,
        ]);

        $teacher = auth('sanctum')->user()->roles->first()->slug;
        if ($request->role == 'expert' && $teacher == 'teacher') {
            TeacherExpert::create([
                'teacher_id' => $user,
                'expert_id' => $user_request,
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
     * login
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function login(Request $request)
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
                'role' => auth()->user()->roles->first()->slug,
                'permission' => auth()->user()->permissions->first()->slug,
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
}
