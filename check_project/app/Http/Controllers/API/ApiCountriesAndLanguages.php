<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiCountriesAndLanguages extends Controller
{

    public function getCountriesAndLanguages(Request $request)
    {
        try {
            $jsonData = file_get_contents(storage_path() . '/json/countries.json');
            $countriesAndLanguages = json_decode($jsonData, true);

            return response()->json(['message' => 'Successfully registered', 'countries' => $countriesAndLanguages]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }
}
