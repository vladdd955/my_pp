<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\PermissionService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isEmpty;

class ApiTaskController extends TaskService
{

    public function userTask(Request $request)
    {
        try {
            $task = $this->getUserTask();

            return response()->json(['message' => 'Success', 'userTask' => $task]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

    public function allTask(Request $request)
    {
        try {
            $allow = PermissionService::allowed(PermissionService::SHOW_ALL_TASK);
            if (!$allow) return response()->json(['error' => 'Permission denied']);
            $allTask = Task::all();

            return response()->json(['message' => 'Success', 'allTask' => $allTask]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

    public function createApiTask(Request $request)
    {
        try {
            $addTask = $request->validate([
                'title' => 'required|string',
                'status' => 'required|string',
                'description' => 'required|string',
            ]);

            $newTask = $this->createTask($addTask);
            if (!$newTask) return response()->json(['error' => 'Error creating task']);

            return response()->json(['message' => 'Success', 'newTask' => true]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

    public function taskStatus(Request $request)
    {
        $taskStatus = $this->getStatus();
        return response()->json(['message' => 'Success', 'taskStatus' => $taskStatus]);
    }

    public function updateApiTask(Request $request)
    {
        try {
            $updateTask = $request->validate([
                'id' => 'required|int',
                'status' => 'string',
                'description' => 'string',
                'user_id' => 'required|int',
            ]);

            $task = $this->getTaskForUpdate($updateTask);
            if (!$task) return response()->json(['error' => 'Permission denied']);
            if (empty($task)) return response()->json(['error' => 'Task not found']);

            $task->status = $updateTask['status'] ?? $task->status;
            $task->description = $updateTask['description'] ?? $task->description;
            $task->user_id = (int)$updateTask['user_id'] ?? $task->user_id;

            $task->save();
            return response()->json(['message' => 'Task Successfully updated', 'task' => $task]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

    public function closeApiTask(Request $request)
    {
        try {
            $closeTask = $request->validate([
                'id' => 'required|int',
                'user_id' => 'required|int',
            ]);

            $task = $this->getTaskForUpdate($closeTask, true);
            if (!$task) return response()->json(['error' => 'Permission denied']);
            if (empty($task)) return response()->json(['error' => 'Task not found']);

            $task->status = TaskService::TASK_S_CLOSED;
            $task->save();

            return response()->json(['message' => 'Task Successfully was closed', 'task' => $task]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }


}
