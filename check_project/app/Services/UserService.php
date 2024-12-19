<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserParam;

class UserService
{

    public static function getParam(string $param, int $user_id): string|null
    {
        return UserParam::where([['param', $param], ['user_id', $user_id]])->value('value') ?? null;
    }

    public static function updateParam(string $param, string $value, int $user_id): string|null
    {
        return UserParam::updateOrCreate(['param' => $param, 'user_id' => $user_id], ['value' => $value]);
    }

    public static function userRole(): ?string
    {
        return UserService::getParam('user_role', User::userId());
    }
}
