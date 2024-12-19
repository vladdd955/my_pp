<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends TaskService
{

    public function index()
    {
        return view('task', [
            'status' => $this->getStatus()
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::id()) abort(403);

        $task = $request->validate([
            'title' => 'required|string',
            'status' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($this->createTask($task)) return redirect('/myTask');
    }


    public function show(int $id)
    {
        $task = Task::findOrFail($id);
        $taskStatus = $this->getStatus();
        $allowedCloseButton = PermissionService::allowed('close_button');
        $usersList = User::getUsers();

        return view('show', [
            'task' => $task,
            'taskStatus' => $taskStatus,
            'usersList' => $usersList,
            'allowedCloseButton' => $allowedCloseButton
        ]);
    }

    public function myTask()
    {
        $tasks = $this->getUserTask();

        return view('myTask', [
            'tasks' => $tasks
        ]);
    }

}
