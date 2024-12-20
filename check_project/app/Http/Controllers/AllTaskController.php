<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\PermissionService;
use App\Services\TaskService;


class AllTaskController extends TaskService
{

    public function index()
    {
        $showAllTask = PermissionService::allowed(PermissionService::SHOW_ALL_TASK);
        if (!$showAllTask) abort(404);

        return view('allTask', [
            'tasks' => Task::all(),
        ]);
    }



}
