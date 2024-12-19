<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function index()
    {

        $tt = $this->testFunc(['fff', 'testCase', 'secondCase'], 0);

        return view('home', ['tt' => $tt]);
    }


    public function testFunc(array $array, int $count): array
    {
        $newArr = [];
        if (!empty($array)) {
            foreach ($array as $key => $val) {
                if (in_array($key, ['testCase', 'secondCase'])) {
                    $newArr[$key] = [
                        $key => $val,
                    ];
                    $count++;
                }
            }
            $newArr['count'] = $count;
        }
        return $newArr;
    }

}

