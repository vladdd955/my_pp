<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends PermissionService
{

    public function index()
    {
        return view('home', [
            'permission' => $this->getRole()
        ]);
    }

    public function confirmRole(Request $request): JsonResponse
    {
        try {
            if (!$request->ajax()) {
                abort(404);
            }

            $permission = $this->permissionValidate($request);
            $result = $this->addRole($permission);

            if (!empty($result['error'])) {
                return response()->json(['error' => $result['error']]);
            }

            if (!empty($result)) {
                return response()->json(['message' => $result['message'], 'currentUserRole' => $result['role']]);
            }
            return response()->json(['error' => 'Result is empty']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Confirm role error']);
        }
    }

    public function deleteRole(Request $request): JsonResponse
    {
        try {
            if (!$request->ajax()) {
                abort(404);
            }

            $permission = $this->permissionValidate($request);
            $result = $this->remRole($permission);

            if (!empty($result)) {
                return response()->json(['message' => $result['message'] ?? '', 'currentUserRole' => $result['role'] ?? '']);
            }
            return response()->json(['error' => 'Result is empty']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Delete role error']);
        }
    }

    public function userRole(Request $request): JsonResponse
    {
        try {
            if (!$request->ajax()) {
                abort(404);
            }
            $userRole = UserService::userRole();
            $userRole = json_decode(json_encode($userRole), true);

            if (!empty($userRole)) {
                return response()->json(['message' => $userRole]);
            }
            return response()->json(['error' => 'Result is empty']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'User role show error']);
        }
    }

}

