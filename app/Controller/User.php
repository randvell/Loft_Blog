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
use Core\Exception\Redirect;

class User extends AbstractController
{
    public function loginAction()
    {
        if (isset($_POST['login'])) {
            $login = $_POST['login'] ?? null;
            $password = $_POST['password'] ?? null;

            $user = UserModel::getByLogin($login);
            if (!$user) {
                $this->view->assign('error', 'Введены неверные данные пользователя');
            }
            if ($user->getPassword() !== UserModel::getPasswordHash($password)) {
                $this->view->assign('error', 'Введены неверные данные пользователя');
            }

            $_SESSION['id'] = $user->getId();

            $this->redirect('/blog/index');
        }

        return $this->view->render('User/register.phtml');
    }

    /**
     * @return string
     * @throws Redirect
     */
    public function registerAction()
    {
        if (isset($_POST['login'])) {
            $gender = 0;
            $name = $_POST['name'] ?? null;
            $login = $_POST['login'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!($success = isset($name, $login, $password))) {
                $success = false;
                $this->view->assign('error', 'Не заполнены обязательные поля');
            }

            if (UserModel::getByLogin($login)) {
                $success = false;
                $this->view->assign('error', 'Такой пользователь уже существует');
            }

            if ($success) {
                $user = (new UserModel())
                    ->setName($name)
                    ->setGender($gender)
                    ->setLogin($login)
                    ->setPassword($password, true);

                $userId = $user->save();

                $_SESSION['id'] = $userId;
                $this->setUser($user);

                $this->redirect('/blog/index');
            }
        }

        return $this->view->render('User/register.phtml');
    }

    /**
     * @return string
     * @throws Redirect
     */
    public function profileAction()
    {
        if (!$user = $this->user) {
            $this->redirect('/user/register');
        }

        return $this->view->render('User/profile.phtml', ['user' => $user]);
    }

    /**
     * @throws Redirect
     */
    public function logoutAction()
    {
        session_destroy();

        $this->redirect('/user/login');
    }
}
