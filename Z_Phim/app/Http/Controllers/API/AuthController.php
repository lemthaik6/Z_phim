<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('API Token')->plainTextToken;
        $request->session()->put('api_token', $token);

        if ($request->wantsJson()) {
            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);
        }

        return redirect()->intended('/movies');
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không chính xác.',
            ])->withInput();
        }

        $request->session()->regenerate();
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('API Token')->plainTextToken;
        $request->session()->put('api_token', $token);

        if ($request->wantsJson()) {
            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
            ]);
        }

        return redirect()->intended($user->is_admin ? '/admin' : '/movies');
    }

    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        $request->session()->forget('api_token');

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request)
    {
        return new UserResource($request->user());
    }
}