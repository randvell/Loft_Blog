<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 19:05
 */

namespace Core;

use Core\Exception\Redirect;

abstract class AbstractController
{
    protected View $view;

    /**
     * @param View $view
     */
    public function setView(View $view): void
    {
        $this->view = $view;
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
