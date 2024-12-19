<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiRoleController extends PermissionService
{

    public function roles(Request $request)
    {
        try {
            $roles = $this->getRole();

            return response()->json(['message' => 'Success', 'roles' => $roles]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

    public function assignRole(Request $request)
    {
        try {
            $permission = $request->validate([
                'permission' => 'required',
                'user_id' => 'required',
            ]);
            $message = 'Success';

            switch ($permission['permission']) {
                case self::GOD_MOD_ROLE:
                    $message .= ' Access granted for GOD_MOD_ROLE';
                    break;
                case self::CLOSE_TASK_BUTTON;
                    $message .= ' Access granted for close task';
                    break;
                case self::UPDATE_TASK_BUTTON;
                    $message .= ' Access granted for update task';
                    break;
                case self::SHOW_ALL_TASK;
                    $message .= ' Access granted for create show all task';
                    break;
                case self::MANAGER;
                    $message .= ' Access granted for create basic manager';
                    break;
                default:
                    $message .= ' Access granted for create basic manager';
            }
            $role = UserService::getParam('user_role', User::userId());
            $role = json_decode($role, true);

            $role[] = $permission['permission'];
            $role = json_encode($role);
            UserService::updateParam('user_role', $role, User::userId());

            return response()->json(['message' => $message, 'currentUserRole' => $role]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

    public function removeRole(Request $request)
    {
        try {
            $permission = $request->validate([
                'permission' => 'required',
                'user_id' => 'required',
            ]);

            $role = UserService::getParam('user_role', User::userId());
            $role = json_decode($role, true);

            if (in_array($permission['permission'], $role)) {
                $key = array_search($permission['permission'], $role);
                if ($key !== false) {
                    unset($role[$key]);
                }

                $role = json_encode($role);
                UserService::updateParam('user_role', $role, User::userId());
                return response()->json([
                    'message' => 'Permission ' . $permission['permission'] . 'was removed',
                    'currentUserRole' => $role
                ]);
            }

            return response()->json(['message' => 'Success', 'roles' => $role]);
        } catch (\Exception $e) {
            Log::debug('register request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }
}
