<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class ApiUserController extends UserService
{

    public function userInfo(Request $request)
    {
        try {
            $user = User::user();
            $user_role = self::userRole();

            return response()->json([
                'message' => 'Success',
                'user' => $user,
                'user_role' => $user_role
            ]);

        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

}
