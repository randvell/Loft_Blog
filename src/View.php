<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 29.01.2022
 * Time: 20:02
 */

namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    public const RENDER_TYPE_NATIVE = 1;
    public const RENDER_TYPE_TWIG = 2;

    private string $templatePath;
    private int $renderType = 1;

    private ?Environment $twig = null;

    private array $data = [];

    /**
     * View constructor.
     */
    public function __construct(int $renderType = self::RENDER_TYPE_NATIVE)
    {
        if ($renderType === self::RENDER_TYPE_NATIVE) {
            $this->templatePath = PROJECT_ROOT_DIR . '/app/View';
        } else {
            $this->templatePath = PROJECT_ROOT_DIR . '\app\Ext\Templates';
        }

        $this->initDataFromSession();
    }

    /**
     * @return int
     */
    public function getRenderType(): int
    {
        return $this->renderType;
    }

    /**
     * @param int $renderType
     *
     * @return View
     */
    public function setRenderType(int $renderType): View
    {
        $this->renderType = $renderType;
        return $this;
    }

    /**
     * Добавит переменную в окружение
     *
     * @param string $name
     * @param $value
     */
    public function assign(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * Рендерим шаблон
     *
     * @param string $tpl
     * @param array $data
     *
     * @return string
     */
    public function render(string $tpl, array $data = []): string
    {
        $this->data += $data;

        ob_start();

        include $this->templatePath . '/' . $tpl;

        $this->assign('error', null);
        $this->assign('result', null);

        return ob_get_clean();
    }

    /**
     * Рендерим шаблон с применением Twig
     *
     * @return Environment
     */
    public function getTwig(string $tpl)
    {
        if (!isset($this->twig)) {
            $path = trim($this->templatePath . '\\' . $tpl, DIRECTORY_SEPARATOR);
            $loader = new FilesystemLoader($path);
            $this->twig = new Environment($loader,
                ['debug' => true, 'auto_reload' => true, 'cache' => 'cache']);
        }

        return $this->twig;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
        $this->$name = $value;
    }

    /**
     * Инициализация параметров из сессии
     */
    private function initDataFromSession(): void
    {
        $result = $_SESSION['result'] ?? null;
        if ($result) {
            $this->assign('result', $result);
            unset($_SESSION['result']);
        }

        $error = $_SESSION['error'] ?? null;
        if ($error) {
            $this->assign('error', $error);
            unset($_SESSION['error']);
        }
    }
}
