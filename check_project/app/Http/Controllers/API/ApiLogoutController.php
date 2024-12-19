<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLogoutController extends Controller
{
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->api_token = null;
        $user->save();
        return response()->json(['code' => 200, 'message' => 'Successfully logged out']);
    }
}
