<?php

namespace App\Http\Controllers;

// use Cookie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $token = $user->createToken('admin')->accessToken;

            $cookie = \cookie('jwt', $token, 3600);

            return \response([
                'token' => $token,
            ])->withCookie($cookie);
        }

        return response([
            'error' => 'Invalid Credentials!',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return \response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(($request->only('first_name', 'last_name', 'email')) + [
            'password' => Hash::make($request->input('password')),
        ]);

        return response($user, 201);
    }
}
