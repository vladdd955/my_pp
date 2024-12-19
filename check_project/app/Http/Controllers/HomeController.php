<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends PermissionService
{

    public function index()
    {

        return view('home', []);
    }


    public function confirmRole(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

//        $this->addRole();

    }

}

