<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 19:04
 */

namespace App\Controller\Api;

use App\Model\Blog as BlogModel;
use Core\AbstractController;

class Blog extends AbstractController
{
    /**
     * Получить последние посты пользователя
     *
     * @return string
     *
     * @url /api/blog/get-posts
     */
    public function getPostsAction(): string
    {
        $userId = $_REQUEST['user_id'] ?? null;
        if (!$userId) {
            return $this->returnJson(['error' => 'Необходимо передать идентификатор пользователя!']);
        }

        $userPosts = BlogModel::getUserPosts($userId);
        return $this->returnJson(['posts' => $userPosts]);
    }
}
