<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\PermissionService;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $jsonData = file_get_contents(storage_path() . '/json/countries.json');
        $countries = json_decode($jsonData, true);

        return view('auth.register', [
            'countries' => $countries,
        ]);
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
        if (!empty($user['Error'])) return abort(404, $user['Error']);

        event(new Registered($user));
        Auth::login($user);

        UserService::updateParam('country', $request->country, User::userId());
        UserService::updateParam('language', $request->language, User::userId());

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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'country' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
        ]);
    }

    public function userReg(Request $request, bool $isApi = false)
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $countryLangValidate = $this->validateCountryAndLanguages($request);
        if (!empty($countryLangValidate['Error'])) return $countryLangValidate;

        return User::create($userData);
    }

    public function getCountryJson(Request $request): JsonResponse
    {
        try {
            if (!$request->ajax()) {
                abort(404);
            }

            $country = $request->input('country');

            $jsonData = file_get_contents(storage_path() . '/json/countries.json');
            $countries = json_decode($jsonData, true);

            $countryData = collect($countries)->firstWhere('country', $country);

            if ($countries) {
                return response()->json([
                    'success' => true,
                    'languages' => $countryData['languages'],
                ]);
            }

            return response()->json(['error' => 'Incorrect country']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Country language show error']);
        }
    }

    public function validateCountryAndLanguages(Request $request): array|bool
    {
        $jsonData = file_get_contents(storage_path() . '/json/countries.json');
        $countries = json_decode($jsonData, true);
        $countryData = collect($countries)->firstWhere('country', $request->country);

        if (is_null($countryData) || $countryData['country'] != $request->country) {
            return ['Error' => 'Invalid Country'];
        }

        $languageExists = in_array($request->language, $countryData['languages']);

        if (!$languageExists) {
            return ['Error' => 'Invalid Language'];
        }

        return true;
    }
}
