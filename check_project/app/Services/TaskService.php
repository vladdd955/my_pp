<?php

namespace App\Services;


use App\Models\Task;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskService
{
    const TASK_S_NORMAL = 'Normal';
    const TASK_S_HIGH  = 'High';
    const TASK_S_IMMEDIATE = 'Immediate';
    const TASK_S_CLOSED = 'Closed';

    protected function getStatus(): array
    {
        return [
            self::TASK_S_HIGH => self::TASK_S_HIGH,
            self::TASK_S_NORMAL => self::TASK_S_NORMAL,
            self::TASK_S_IMMEDIATE => self::TASK_S_IMMEDIATE,
        ];
    }

    public function getUserTask()
    {
        return Task::where('user_id', Auth::id())
            ->get();
    }

    protected function createTask(array $data): bool
    {
        if (!empty($data)) {
            Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'user_id' => Auth::id(),
            ]);

            return true;
        }

        return false;
    }

    public function closeTask(Request $request): JsonResponse
    {
        $permission = PermissionService::allowed(PermissionService::CLOSE_TASK_BUTTON);
        if (!$permission) return response()->json(['error' => 'Permission denied']);

        $task = Task::find($request->input('taskId'));

        if ($task) {
            $task->status = self::TASK_S_CLOSED;
            $task->save();
            return response()->json(['message' => 'Task closed successfully']);
        } else {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    public function updateTask(Request $request): JsonResponse
    {
        $permission = PermissionService::allowed(PermissionService::UPDATE_TASK_BUTTON);
        if (!$permission) return response()->json(['error' => 'Permission denied']);

        $task = Task::find($request->input('taskId'));
        $user = User::userId();

        if ($task && $user) {
            $task->status = $request->input('newStatus');
            $task->user_id = $request->input('userId');

            $task->save();
            return response()->json(['message' => 'Task update successfully']);
        } else {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    public function getTaskForUpdate(array $updateTaskData, bool $closeTask = false): Task|false
    {
        if ($closeTask) {
            $closePermission = PermissionService::allowed(PermissionService::CLOSE_TASK_BUTTON);
            if (!$closePermission) return false;
        } else {
            $updatePermission = PermissionService::allowed(PermissionService::UPDATE_TASK_BUTTON);
            if (!$updatePermission) return false;
        }

        if ($updateTaskData['user_id'] != User::userId()) {
            $allTaskPermission = PermissionService::allowed(PermissionService::SHOW_ALL_TASK);

            if (!$allTaskPermission) {
                $result = false;
            } else {
                $result = Task::getTaskById($updateTaskData['id']);
            }

        } else {
            $result = Task::getTaskById($updateTaskData['id']);
        }

        return $result;
    }


}
