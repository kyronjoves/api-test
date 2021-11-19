<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $token = true;

    public function register(Request $request)
    {

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'registered_at' => Carbon::now()
            ]);
            DB::commit();
            return response()->json(
                [
                    'status' => 'success',
                    'result' => $user,

                ], Response::HTTP_OK
            );
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Error. Please try again.',
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }

    public function logout(Request $request)
    {
        // $this->validate($request, [
        //     'token' => 'required'
        // ]);
        try {
            JWTAuth::invalidate($request->bearerToken());

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }
}
