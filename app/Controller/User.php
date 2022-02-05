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
use Core\Exception\Validation as ValidationException;

class User extends AbstractController
{
    public function loginAction()
    {
        try {
            if (isset($_POST['email'])) {
                $login = $_POST['email'];
                $password = $_POST['password'] ?? null;

                $user = UserModel::getByEmail($login);
                if (!$user) {
                    throw new ValidationException('Введены неверные данные пользователя');
                }
                if ($user->getPassword() !== UserModel::getPasswordHash($password)) {
                    throw new ValidationException('Введены неверные данные пользователя');
                }

                $_SESSION['id'] = $user->getId();

                $this->redirect('/blog/index');
            }
        } catch (ValidationException $e) {
            $this->setError($e->getMessage());
        }

        return $this->view->render('User/register.phtml');
    }

    /**
     * @return string
     * @throws Redirect
     */
    public function registerAction()
    {
        try {
            if (isset($_POST['email'])) {
                $gender = 0;
                $name = $_POST['name'] ?? null;
                $email = $_POST['email'] ?? null;
                $password = $_POST['password'] ?? null;
                $passwordConfirmation = $_POST['password_confirmation'] ?? null;

                if (!isset($name, $email, $password)) {
                    throw new ValidationException('Не заполнены обязательные поля');
                }
                if (mb_strlen($password) < 4) {
                    throw new ValidationException('Пароль не соответствует политике безопасности');
                }
                if ($password !== $passwordConfirmation) {
                    throw new ValidationException('Введенные пароли не совпадают');
                }
                if (UserModel::getByEmail($email)) {
                    throw new ValidationException('Такой пользователь уже существует');
                }

                $user = (new UserModel())
                    ->setName($name)
                    ->setGender($gender)
                    ->setEmail($email)
                    ->setPassword($password, true);

                $userId = $user->save();

                $_SESSION['id'] = $userId;
                $this->setUser($user);

                $this->redirect('/blog/index');
            }
        } catch (ValidationException $e) {
            $this->setError($e->getMessage());
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
