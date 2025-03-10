<?php

namespace App\Http\Controllers;

use App\Http\Requests\authRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


class AuthController extends Controller
{

    public function register(authRequest  $request)
    {
        $user = User::create([
            'fullname' => $request->fullname,    //  روح خزن requ  يلي  اجا من حقل name      =خزنه name بعد ماتحقق منه
            'email' => $request->email,
            'password' => Hash::make($request['password']),
            'role' => $request->role,
        ]);


        event(new Registered($user));
        // $request->user()->sendEmailVerificationNotification();

        $token = $user->createToken("API TOKEN")->plainTextToken;

        return (new UserResource($user))->additional([
            'meta' => [
                'status' => 1,
                'token' => $token,
                'message' => 'User Register successfully'
            ]
        ]);
    }
    // -----------------------------------------------------------------------------------------------
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            $message = 'Email & Password Does Not Match With Our Record .';
            return response()->json([
                'data' => [],
                'status' => 0,
                'massage' => $message
            ], 500);
        }

        $user = User::query()->where('email', '=', $request['email'])->first();
        // $role = $user->role;


        $token = $user->createToken("API TOKEN")->plainTextToken;

        return (new UserResource($user))->additional([
            'meta' => [
                'status' => 1,
                'token' => $token,
                'message' => 'User logged in successfully'
            ]
        ]);
    }

    // ------------------------------------------------------

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 1,
            'message' => 'User logged out successfully.'
        ]);
    }
    // -----------------------------------------------------------

    // Verify Email 
    public function VerifyNotice()
    {
        return response()->json(['message' => 'VerifyNotice successfully'], 201);
    }

    public function VerifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return response()->json(['message' => 'verfiy Email Handling successfully'], 201);
    }


    public function ResendEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent!'], 201);
    }
}
