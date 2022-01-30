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
    public function indexAction()
    {
        if (!$user = $this->user) {
            $this->redirect('/user/register');
        }

        return $this->view->render('Blog/index.phtml', ['user' => $user]);
    }
}
