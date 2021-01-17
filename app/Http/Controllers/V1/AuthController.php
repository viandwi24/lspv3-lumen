<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => [
                'profile',
                'profile_update',
                'profile_change_password'
            ]
        ]);
    }

    /**
     * Get auth profile
     *
     * @return void
     */
    public function profile()
    {
        $user = Auth::user();

        return apiResponse(
            ($user),
            "Get auth profile success.",
            true,
            null, [],
            200
        );
    }


    /**
     * Prifule - Update
     *
     * @param Request $request
     * @return void
     */
    public function profile_update(Request $request)
    {
        // 
        $user = Auth::user();

        // make validator
        $rules = ['name' => 'required|string|min:3'];
        if ($request->email != $user->email) $rules['email'] = 'required|string|unique:users';
        if ($request->phone != $user->phone) $rules['phone'] = 'required|numeric|unique:users|min:12';
        if ($request->signature != $user->signature) $rules['signature'] = 'required|string';
        if ($request->identity_number != $user->identity_number) $rules['identity_number'] = 'required|string';
        if ($request->identity_number_type != $user->identity_number_type) $rules['identity_number_type'] = 'required|string|in:NIK,NIS,SIM,Custom';
        $validator = Validator::make($request->all(), $rules);

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // update
        $update = null;
        DB::transaction(function () use ($user, $request, &$update) {
            $update = $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'signature' => $request->signature,
                'identity_number' => $request->identity_number,
                'identity_number_type' => $request->identity_number_type
            ]);
        });
        return apiResponse(
            $user,
            "Update user profile success.",
            true,
            null, [],
            200
        );
    }

    /**
     * Profile - Change Password
     *
     * @param Request $request
     * @return void
     */
    public function profile_change_password(Request $request)
    {
        // 
        $user = Auth::user();

        // validator
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:5|max:16|confirmed'
        ]);

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // update
        $update = null;
        DB::transaction(function () use ($user, $request, &$update) {
            $update = $user->update([
                'password' => Hash::make($request->password),
            ]);
        });
        return apiResponse(
            $user,
            "Change user password success.",
            true,
            null, [],
            200
        );
    }

    /**
     * Login
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'email' => 'required|string|email',
            'password' => 'required|string|min:3'
        ]));

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // set credentials
        $credentials = $request->only(['email', 'password']);

        // attempt
        if (!$token = Auth::attempt($credentials)) {
            return apiResponse(
                $request->all(),
                "These credentials do not match our records.",
                false,
                'auth.login.attempt',
                [],
                401
            );
        }

        // get user
        $user = Auth::user();

        // make history
        $this->createLoginHistory($user, $request);

        // response token
        return apiResponse(
            (new UserResource($user)),
            "Get auth token success.",
            true,
            null, [],
            200,
            [ 'credentials' => $this->respondWithToken($token) ]
        );

    }

    /**
     * Register
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        // make validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string|unique:users|min:5|max:13',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:5|max:16|confirmed',
            'phone' => 'required|numeric|min:8|max:12|unique:user_detail|phone_number',
        ]);

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );
        
        // 
        $store = null;
        DB::transaction(function () use ($request, &$store) {
            // store new user
            $store = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone
            ]);
        });

        // response token
        return apiResponse(
            (new UserResource($store)),
            "Register success.",
            true,
            null, [],
            201
        );
    }

    /**
     * Get User Info
     *
     * @param Request $request
     * @return void
     */
    public function user(Request $request)
    {
        $logged_user = Auth::user();
        return apiResponse(
            $logged_user,
            "Success get user data.",
            true
        );
    }

    /**
     * Create login history
     *
     * @param User $user
     * @param Request $request
     * @return void
     */
    protected function createLoginHistory(User $user, Request $request) : void
    {
        $user_agent = $request->header('User-Agent');
        $ip_address = $request->ip();

        // store new history
        // $user->login_histories()->create([
        //     'user_agent' => $user_agent,
        //     'ip_address' => $ip_address
        // ]);

        // update last login without updated_at
        $user->timestamps = false;
        $user->last_login = Carbon::now();
        $user->save();
        $user->timestamps = true;
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        if(Auth::check()) {
            Auth::logout();
        }
        return apiResponse(
            [],
            'Success logout',
            200
        );
    }

    /**
     * Response credentials
     *
     * @param string $token
     * @return array
     */
    protected function respondWithToken(string $token) : array
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}