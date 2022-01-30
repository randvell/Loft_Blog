<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 30.01.2022
 * Time: 15:01
 */

namespace App\Model;

use Core\AbstractModel;
use Core\Db;

class Blog extends AbstractModel
{
    /**
     * Получить последние N постов пользователя
     *
     * @param int $userId
     * @param int $limit
     *
     * @return array
     */
    public static function getUserPosts(int $userId, int $limit = 20): array
    {
        return self::getPosts($userId, $limit);
    }

    /**
     * Получить посты из блога
     *
     * @param int|null $userId
     * @param int $limit
     * @param bool $addUserName
     * @param bool $returnModels
     *
     * @return Post[]|array
     */
    public static function getPosts(?int $userId = null, int $limit = 20, bool $addUserName = true, bool $returnModels = false)
    {
        $db = Db::getInstance();

        $select = '*';
        if ($addUserName) {
            $select = 'posts.*, name as user_name';
        }

        $query = "SELECT $select FROM posts";
        if ($addUserName) {
            $query .= ' LEFT JOIN users ON posts.user_id = users.id';
        }
        if (!is_null($userId)) {
            $query .= " WHERE user_id = $userId";
        }
        if (!is_null($limit)) {
            $query .= " LIMIT $limit";
        }

        $data = $db->fetchAll($query);

        if (!$data) {
            return [];
        }

        if (!$returnModels) {
            return $data;
        }

        return array_map(static function ($postData) {
           return new Post($postData);
        }, $data);
    }
}
