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

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->templatePath = PROJECT_ROOT_DIR . '/app/View';
    }

    /**
     * Рендерим шаблон
     *
     * @param string $tpl
     * @param array $params
     *
     * @return string
     */
    public function render(string $tpl, array $params = []): string
    {
        extract($params, EXTR_OVERWRITE);
        ob_start();
        include $this->templatePath . '/' . $tpl;
        return ob_get_clean();
    }
}
