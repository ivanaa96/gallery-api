<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Exceptions\UserNotDefinedException;

class UserController extends Controller
{
    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $token = Auth::login($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    public function getMyProfile()
    {
        $activeUser = Auth::user();
        return response()->json($activeUser);
    }

    public function logout()
    {
        Auth::logout();
        return response()->noContent();
    }

    public function refreshToken()
    {
        try {
            $token = Auth::refresh();
            return [
                'token' => $token,
            ];
        } catch (UserNotDefinedException $exception) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }
    }
}
