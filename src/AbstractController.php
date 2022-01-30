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
use JsonException;

abstract class AbstractController
{
    protected View $view;
    protected ?User $user = null;

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
     * Добавить информацию об ошибке во вью
     *
     * @param string $value
     */
    public function setError(string $value): void
    {
        $this->view->assign('error', $value);
    }

    /**
     * Добавить сообщение о результате выполнения
     *
     * @param string $value
     */
    public function setResult(string $value): void
    {
        $this->view->assign('result', $value);
    }

    /**
     * Добавить информацию об ошибке во вью
     *
     * @param string $value
     */
    public function setErrorToSession(string $value): void
    {
        $_SESSION['error'] = $value;
    }

    /**
     * Добавить сообщение о результате выполнения
     *
     * @param string $value
     */
    public function setResultToSession(string $value): void
    {
        $_SESSION['result'] = $value;
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

    /**
     * @param array $data
     *
     * @return string
     */
    protected function returnJson(array $data): string
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
