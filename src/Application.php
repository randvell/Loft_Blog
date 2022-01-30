<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 18:51
 */

namespace Core;

use App\Controller\Blog;
use App\Controller\User;
use App\Model\User as UserModel;
use Core\Exception\Redirect;
use Core\Exception\Route as RouteException;

class Application
{
    private Router $router;
    private AbstractController $controller;

    private ?string $actionName = null;

    /**
     * Application constructor
     */
    public function __construct()
    {
        $this->router = new Router();
    }

    /**
     * Обработать запрос
     */
    public function run(): void
    {
        try {
            session_start();
            $this->addRoutes();
            $this->initController();
            $this->initAction();

            $view = new View();
            $this->controller->setView($view);
            $this->initUser();

            $content = $this->controller->{$this->actionName}();

            echo $content;
        } catch (Redirect $r) {
            header('Location: ' . $r->getUrl());
        } catch (RouteException $e) {
            echo $e->getMessage();
            header('HTTP/1.0 404 Not Found');
        }
    }

    /**
     * Установка контекста пользователя из сессии
     */
    private function initUser(): void
    {
        $id = $_SESSION['id'] ?? null;
        if (is_null($id)) {
            return;
        }

        $user = UserModel::getById($id);
        if (!$user) {
            return;
        }

        $this->controller->setUser($user);
    }

    /**
     * Установка доступных роутов
     */
    private function addRoutes(): void
    {
        /** @uses User::loginAction() */
        $this->router->addRoute('/user/login', User::class, 'login');

        /** @uses User::registerAction() */
        $this->router->addRoute('/user/register', User::class, 'register');

        /** @uses Blog::indexAction() */
        $this->router->addRoute('/blog', Blog::class);
    }

    /**
     * @throws RouteException
     */
    private function initController(): void
    {
        $controllerName = $this->router->getControllerName();
        if (!class_exists($controllerName)) {
            throw new RouteException('Can\'t find controller ' . $controllerName);
        }

        $this->controller = new $controllerName();
    }

    /**
     * @throws RouteException
     */
    private function initAction(): void
    {
        $action = $this->router->getActionName();
        if (!method_exists($this->controller, $action)) {
            throw new RouteException("Action $action not found in {$this->router->getControllerName()}");
        }

        $this->actionName = $action;
    }
}
