<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 30.01.2022
 * Time: 17:07
 */

namespace Core;

class Administration
{
    private static array $userIds = [1, 3, 5];

    /**
     * Проверка является ли пользователь администратором
     *
     * @param int $userId
     * @return bool
     */
    public static function getIsAdmin(int $userId): bool
    {
        return in_array($userId, self::$userIds, true);
    }
}
