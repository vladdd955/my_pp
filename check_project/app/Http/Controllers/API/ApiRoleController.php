<?php

namespace App\Http\Controllers\API;

use App\Services\PermissionService;
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
            $permission = $this->permissionValidate($request);
            $result = $this->addRole($permission);

            if (!empty($result['error'])) {
                return response()->json(['error' => $result['error']]);
            }

            return response()->json(['message' => $result['message'], 'currentUserRole' => $result['role'] ?? '']);
        } catch (\Exception $e) {
            Log::debug('Assign role request error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }

    public function removeRole(Request $request)
    {
        try {
            $permission = $this->permissionValidate($request);
            $result = $this->remRole($permission);

            if (!empty($result['message'])) return response()->json(['error' => $result['message']]);

            return response()->json([
                    'message' => 'Permission ' . $result['permission'] . ' was removed',
                    'currentUserRole' => $result['role']
                ]);
        } catch (\Exception $e) {
            Log::debug('Remove role error', [$e->getMessage()]);
            return '400 ' . 'error: ' . $e->getMessage();
        }
    }
}
