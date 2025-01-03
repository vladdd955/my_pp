<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiUserIsLoggedInController extends Controller
{
    public function testUser()
    {
        $user = Auth::user();
        if ($user) {
            return response()->json(['code' => 200, 'message' => 'user is logged in', 'user' => $user]);
        } else {
            return response()->json(['code' => 400, 'message' => 'user is not logged in']);
        }
    }
}
