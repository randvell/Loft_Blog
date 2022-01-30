<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 19:05
 */

namespace Core;

use App\Model\User;
use Core\Exception\Redirect;

abstract class AbstractController
{
    protected View $view;
    protected ?User $user;

    /**
     * @param View $view
     */
    public function setView(View $view): void
    {
        $this->view = $view;
    }

    /**
     * Установление контекста пользователя
     *
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Выполнение переадресации
     *
     * @param string $url
     * @throws Redirect
     */
    protected function redirect(string $url): void
    {
        throw new Redirect($url);
    }
}
