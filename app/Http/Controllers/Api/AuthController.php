<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $isApiResource = false;

    // send csrf token to a client/frontend app
    public function csrf(Request $request) {
        return response()->json(['csrf_token' => csrf_token()], 200);
    }


    public function authenticate(Request $request) {
        $valid = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        if (!auth()->attempt($valid)) {
            return response()->json(['message' => 'Invalid credentials!'], 422);
        }

        $user = auth()->user();
        $token = JWTAuth::fromUser($user);
        
        return response()->json([
            'user' => array_merge($user->toArray(), [
                'roles' => $user->getRoleNames(),
            ]), 
            'accessToken' => $token
        ], 200);
        
    }

    public function logout(Request $request) {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('id', $request->user()->id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->tokens()->delete();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function register(Request $request) {
        $valid = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);

        $valid['password'] = bcrypt($valid['password']);
        $user = User::create($valid);
        $token = JWTAuth::fromUser($user);

        return response()->json(['user' => $user, 'accessToken' => $token], 201);
    }

    public function me(Request $request) {
        return response()->json([
            'user' => $request->user(),
            'roles' => $request->user()->getRoleNames(),
        ], 200);
    }

    public function refresh(Request $request) {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('id', $request->user()->id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->tokens()->delete();
        JWTAuth::invalidate(JWTAuth::getToken());
        $newToken = JWTAuth::fromUser($user);
        return response()->json(['token' => $newToken], 200);
    }

    public function forgotPassword(Request $request) {
        $valid = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $valid['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $resetToken = JWTAuth::fromUser($user);
        return response()->json(['resetToken' => $resetToken], 200);
    }

    public function resetPassword(Request $request) {
        $valid = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'resetToken' => 'required|string',
        ]);

        $user = User::where('email', $valid['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = JWTAuth::parseToken($valid['resetToken']);
        if (!$token->check()) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $user->password = bcrypt($valid['password']);
        $user->save();
        $token->revoke();
        return response()->json(['message' => 'Password reset successfully'], 200);
    }

    
}
