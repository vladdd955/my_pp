<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\UserService;
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
            $token = $user->createToken('API Token')->plainTextToken;

            //permission && county - language
            PermissionService::createProcess('manager');
            UserService::updateParam('country', $request->country, User::userId());
            UserService::updateParam('language', $request->language, User::userId());

            return response()->json(['message' => 'Successfully registered', 'token' => $token]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }

    }
}
