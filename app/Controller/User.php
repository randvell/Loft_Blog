<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 18:44
 */

namespace App\Controller;

use Core\AbstractController;

class User extends AbstractController
{
    public function loginAction(): void
    {
        echo __METHOD__;
    }

    public function registerAction(): void
    {
        echo __METHOD__;
    }
}
