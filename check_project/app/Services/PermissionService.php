<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserParam;

class PermissionService
{

    //need cleanup
    const GOD_MOD_ROLE = 'god_mod';
    const CLOSE_TASK_BUTTON = 'close_button';
    const UPDATE_TASK_BUTTON = 'update_button';
    const SHOW_ALL_TASK = 'show_all_task';
    const MANAGER = 'manager';

    protected function getRole(): array
    {
        return [
            self::GOD_MOD_ROLE => self::GOD_MOD_ROLE,
            self::CLOSE_TASK_BUTTON => self::CLOSE_TASK_BUTTON,
            self::UPDATE_TASK_BUTTON => self::UPDATE_TASK_BUTTON,
            self::SHOW_ALL_TASK => self::SHOW_ALL_TASK,
            self::MANAGER => self::MANAGER,
        ];
    }


    public static function allowed($permission): bool
    {
        if (self::isGodModOn()) return true;

        $userRole = self::getUserRole();
        $userRole = json_decode($userRole, true);

        if (!empty($userRole)) {
            if (in_array($permission, $userRole)) {
                return true;
            }
        }

        return false;
    }

    protected static function isGodModOn(): bool
    {
        $userRole = UserService::getParam('user_role', User::userId());
        $userRole = json_decode($userRole, true);

        if (!empty($userRole) && in_array(self::GOD_MOD_ROLE, $userRole)) return true;

        return false;
    }

    protected static function getUserRole(): string|null
    {
        return UserService::getParam('user_role', User::userId());
    }

    public static function createProcess(string $role): void
    {
        $permission = explode(' ', trim($role));
        $role = json_encode($permission);
        UserParam::create([
            'param' => 'user_role',
            'value' => $role,
            'user_id' => User::userId(),
        ]);
    }

    protected function addRole(array $permission): array
    {
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

        $result['message'] = $message;
        $role = UserService::getParam('user_role', User::userId());
        $role = json_decode($role, true);

        $role[] = $permission['permission'];
        $role = json_encode($role);
        UserService::updateParam('user_role', $role, User::userId());
        $result['role'] = $role;

        return $result;
    }

    protected function remRole(array $permission): array
    {
        $role = UserService::getParam('user_role', User::userId());
        $role = json_decode($role, true);

        if (in_array($permission['permission'], $role)) {
            $key = array_search($permission['permission'], $role);
            if ($key !== false) {
                unset($role[$key]);
            }

            $role = json_encode($role);
            UserService::updateParam('user_role', $role, User::userId());
            $result['role'] = $role;
            $result['permission'] = $permission['permission'];
        } else {
            $result['message'] = 'Do not have that permission ' . $permission['permission'];
        }

        return $result;
    }

}
