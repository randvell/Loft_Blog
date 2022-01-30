<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 30.01.2022
 * Time: 14:30
 */

namespace App\Model;

use Core\AbstractModel;
use Core\Db;

class Post extends AbstractModel
{
    private ?int $id;
    private ?int $userId;
    private ?string $message;
    private ?string $createdAt;

    private ?string $userName = null;

    /**
     * Post constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            $this->id = $data['id'];
            $this->userId = $data['user_id'];
            $this->message = $data['message'];
            $this->createdAt = $data['created_at'];

            if (isset($data['user_name'])) {
                $this->userName = $data['user_name'];
            }
        }
    }

    /**
     * Удаление поста по идентификатору
     *
     * @param int $id
     */
    public static function deleteById(int $id)
    {
        $query = "DELETE FROM posts where id = $id";
        $Db = Db::getInstance();
        $Db->exec($query);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return Post
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Post
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * Сохранить сообщение в базе
     *
     * @return int|null
     */
    public function save(): ?int
    {
        $db = Db::getInstance();
        $insert = 'INSERT INTO posts (`user_id`, `message`) VALUES (:user_id, :message)';
        $db->exec($insert, [':user_id' => $this->getUserId(), ':message' => $this->getMessage()]);

        $id = $db->getLastInsertId();
        if (is_null($id)) {
            throw new \RuntimeException('Не удалось сохранить сообщение');
        }
        $this->id = $id;

        return $id;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }
}
