<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 19:04
 */

namespace App\Controller;

use Core\AbstractController;

class Blog extends AbstractController
{
    public function indexAction(): void
    {
        if (isset($_GET['redirect'])) {
            $this->redirect($_GET['redirect']);
        }

        echo __METHOD__;
    }
}
