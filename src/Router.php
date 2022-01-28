<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 19:08
 */

namespace Core;

class Router
{
    private ?string $controllerName = null;
    private ?string $actionName = null;

    private array $routes = [];

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        if (!isset($this->controllerName)) {
            $this->process();
        }

        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        if (!isset($this->actionName)) {
            $this->process();
        }

        return $this->actionName . 'Action';
    }

    /**
     *
     */
    private function process(): void
    {
        if ($this->actionName && $this->controllerName) {
            return;
        }

        $parts = parse_url($_SERVER['REQUEST_URI']);
        $path = $parts['path'];
        $route = $this->routes[$path] ?? null;
        if ($route) {
            $this->controllerName = $route[0];
            $this->actionName = $route[1];
        } else {
            $parts = explode('/', $path);
            $this->controllerName = '\\App\\Controller\\' . ucfirst(strtolower($parts[1]));
            $this->actionName = strtolower($parts[2] ?? 'index');
        }
    }

    /**
     * Add route
     *
     * @param string $path
     * @param string $controllerName
     * @param string $action
     *
     * @return $this
     */
    public function addRoute(string $path, string $controllerName, string $action = 'index'): self
    {
        $this->routes[$path] = [
            $controllerName,
            $action
        ];

        return $this;
    }
}
