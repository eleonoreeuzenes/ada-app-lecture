<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    /**
     * Login a user and return an access token.
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
       $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login information is invalid.'
            ], 400);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);

    }

    /**
     * Register a new user and return an access token.
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $validatorEmail = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validatorEmail->fails()) {
            return response()->json([
                'message' => 'Email format is invalid.'
            ], 400);
        }

        if (User::where('email', $request['email'])->count() > 0) {
            return response()->json([
                'message' => 'Email already used.'
            ], 409);
        }

        $validatorUsername = Validator::make($request->all(), [
            'username' => 'required|string|min:5|max:29'
        ]);

        if ($validatorUsername->fails()) {
            return response()->json([
                'message' => 'Username format is invalid (it musts contain between 5 & 29 characters).'
            ], 400);
        }

        if (User::where('username', $request['username'])->count() > 0) {
            return response()->json([
                'message' => 'Username already used.'
            ], 409);
        }

        $validatorPassword = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:8',
                'regex:/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*$/'
            ]
        ]);

        if ($validatorPassword->fails()) {
            return response()->json([
                'message' => 'Password format is invalid.'
            ], 400);
        }

        $user = User::create([
            'email' => $request['email'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
        ]);
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout the user and revoke the access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }
}
