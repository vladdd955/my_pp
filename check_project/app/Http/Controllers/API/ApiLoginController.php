<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                Auth::login($user, true);

                $token = $user->createToken('API Token')->plainTextToken;

                return response()->json(['code'=> 200, 'message' => 'Successfully logged in', 'token' => $token]);
            }

            return response()->json(['code'=> 400, 'message' => 'Invalid credentials']);
        } catch (\Exception $e) {
            Log::debug('Login request error', [$e->getMessage()]);
            return '803 ' . 'error: ' . $e->getMessage();
        }
    }
}
