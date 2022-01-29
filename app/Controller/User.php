<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 18:44
 */

namespace App\Controller;

use App\Model\User as UserModel;
use Core\AbstractController;

class User extends AbstractController
{
    public function loginAction(): void
    {
        echo __METHOD__;
    }

    public function registerAction()
    {
        $name = 'Nikita';
        $gender = UserModel::GENDER_MALE;
        $password = '12345';

        $user = (new UserModel())
            ->setName($name)
            ->setGender($gender)
            ->setPassword($password, true);

        $userId = $user->save();

        return $this->view->render('User/register.phtml', ['user' => $user]);
    }

    public function profileAction()
    {
        return $this->view->render('User/profile.phtml', ['user' => UserModel::getById((int)$_GET['id'])]);
    }
}
