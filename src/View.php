<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 29.01.2022
 * Time: 20:02
 */

namespace Core;

class View
{
    private string $templatePath;
    private $data = [];

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->templatePath = PROJECT_ROOT_DIR . '/app/View';
    }

    /**
     * Добавит переменную в окружение
     *
     * @param string $name
     * @param $value
     */
    public function assign(string $name, $value)
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
        return ob_get_clean();
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
}
