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

}
