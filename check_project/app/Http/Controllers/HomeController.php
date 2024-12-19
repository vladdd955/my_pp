<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends PermissionService
{

    public function index()
    {
        return view('home', [
            'role' => $this->getRole()
        ]);
    }

    public function confirmRole(Request $request): JsonResponse
    {
        try {
            if (!$request->ajax()) {
                abort(404);
            }

            Log::debug('confirm funx');
            Log::debug($request);

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

            Log::debug('confirm funx');
            Log::debug($request);

            $permission = $this->permissionValidate($request);
            $result = $this->remRole($permission);

            Log::debug($result);

            if (!empty($result)) {
                return response()->json(['message' => $result['message'], 'currentUserRole' => $result['role'] ?? '']);
            }
            return response()->json(['error' => 'Result is empty']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Delete role error']);
        }
    }

}

