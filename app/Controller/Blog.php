<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 19:04
 */

namespace App\Controller;

use App\Model\Blog as BlogModel;
use App\Model\Post;
use Core\AbstractController;
use Core\Exception\Redirect;
use Core\Exception\Validation as ValidationException;
use Core\Helper;
use Throwable;

class Blog extends AbstractController
{
    /**
     * @return string
     * @throws Redirect
     */
    public function indexAction()
    {
        if (!$user = $this->user) {
            $this->redirect('/user/register');
        }

        if ($this->view->getRenderType() === $this->view::RENDER_TYPE_NATIVE) {
            $result = $this->view->render('Blog/index.phtml',
                ['user' => $user, 'posts' => BlogModel::getPosts(returnModels: true)]);
        } else {
            try {
                $result = $this->view->getTwig('Blog\Index')->render('index.twig',
                    ['user' => $user, 'posts' => BlogModel::getPosts(returnModels: true)]);
            } catch (Throwable $e) {
                return 'Не удалось загрузить страницу';
            }
        }

        return $result;
    }

    /**
     * @throws Redirect
     */
    public function postAction()
    {
        if (!$user = $this->user) {
            $this->redirect('/user/register');
        }

        try {
            $message = $_POST['message'] ?? null;
            if (!$message) {
                throw new ValidationException('Невозможно отправить пустое сообщение');
            }

            if (!empty($_FILES['image']['name'])) {
                if (!getimagesize($_FILES['image']['tmp_name'])) {
                    throw new ValidationException('Разрешена загрузка только изображений!');
                }

                $result = Helper::saveToMedia(file_get_contents($_FILES['image']['tmp_name']), md5(time()) . '.jpg');
                $imagePath = $result['relative_path'];
                $message .= '<p><img src="' . $imagePath . '" size="100px"></p>';
            }

            (new Post())
                ->setMessage($message)
                ->setUserId($user->getId())
                ->save();

            $this->setResultToSession('Сообщение успешно отправлено');
        } catch (ValidationException $e) {
            $this->setErrorToSession($e->getMessage());
        }

        $this->redirect('/blog/index');
    }

    /**
     * Удаление поста пользователя
     *
     * @throws Redirect
     *
     * @url /blog/delete?id=1
     */
    public function deletePostAction()
    {
        if (!$user = $this->user) {
            $this->redirect('/user/register');
        }
        try {
            if (!$user->getIsAdmin()) {
                throw new ValidationException('Невозможно удалить пост без прав администратора');
            }

            $id = $_GET['id'] ?? null;
            if (is_null($id)) {
                throw new ValidationException('Для удаление поста необходимо передать его идентификатор');
            }

            Post::deleteById($id);

            $this->setResultToSession('Сообщение успешно удалено');
        } catch (ValidationException $e) {
            $this->setErrorToSession($e->getMessage());
        }

        $this->redirect('/blog/index');
    }
}
