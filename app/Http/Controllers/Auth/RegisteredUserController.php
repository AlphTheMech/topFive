<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Permission;
use App\Models\Role;
use App\Models\PersonalData;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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
      
		 //$user->givePermissionsTo('administration');
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
        $cookie = cookie('jwt', $token, 60 * 24 * 3);
        return response()->json([
            'token' => $token,
            'code'=>201
            
        ],201)->withCookie($cookie);
    }
    //     public function store(Request $request){
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
    // //     return response()->json()->setStatusCode(204);
    // }
}
