<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiRegistrationController extends Controller
{
    public function register(Request $request)
    {
        try {
            $user = (new RegisteredUserController())->apiRegister($request);
            event(new Registered($user));

            Auth::login($user);
            PermissionService::createProcess('manager');

            return response()->json(['message' => 'Successfully registered', 'token' => $user->api_token]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }

    }
}
