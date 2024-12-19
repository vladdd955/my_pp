<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\PermissionService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->registerValidate($request);
        $user = $this->userReg($request);

        event(new Registered($user));

        Auth::login($user);

        PermissionService::createProcess('manager');
        return redirect(RouteServiceProvider::HOME);
    }

    public function apiRegister(Request $request)
    {
        $this->registerValidate($request);
        return $this->userReg($request, true);
    }

    public function registerValidate(Request $request): void
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    }

    public function userReg(Request $request, bool $isApi = false)
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($isApi) {
            $userData['api_token'] = Str::random(60);
        }

        return User::create($userData);
    }
}
