<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validate = Validator::make($credentials, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validate->fails() || !Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.'
            ]);
        }

        $token = $request->user()->createToken('global')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'expired_at' => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->user()->tokens()->delete();

        return response()->json(['success' => true]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
