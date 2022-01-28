<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 21:07
 */

namespace Core\Exception;

class Redirect extends \Exception
{
    private string $url;

    public function __construct(string $url)
    {
        parent::__construct();
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
