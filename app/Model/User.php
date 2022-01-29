<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 29.01.2022
 * Time: 20:21
 */

namespace App\Model;

use Core\AbstractModel;
use Core\Db;

/**
 * Class User
 *
 * @package App\Model
 */
class User extends AbstractModel
{
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;

    private ?int $id;
    private ?string $name;
    private ?string $password;
    private ?string $createdAt;
    private ?int $gender;

    /**
     * AbstractModel constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->password = $data['password'];
            $this->gender = $data['gender'];
            $this->createdAt = $data['created_at'];
        }
    }

    /**
     * Получение имени пользователя
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @param bool $useHash
     *
     * @return $this
     */
    public function setPassword(string $password, bool $useHash = false): self
    {
        if ($useHash) {
            $password = self::getPasswordHash($password);
        }
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getGenderString(): string
    {
        if (!$gender = $this->getGender()) {
            return 'unknown';
        }

        return $gender === self::GENDER_MALE ? 'male' : 'female';
    }

    /**
     * @param int $gender
     *
     * @return $this
     */
    public function setGender(int $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Создать пользователя
     *
     * @return int|null
     */
    public function save(): ?int
    {
        $db = Db::getInstance();
        $insert = 'INSERT INTO users (`name`, `password`, `gender`) VALUES (:name, :password, :gender)';
        $db->exec($insert, [':name' => $this->getName(), 'password' => $this->getPassword(), ':gender' => $this->getGender()]);

        $id = $db->getLastInsertId();
        if (is_null($id)) {
            throw new \RuntimeException('Не удалось сохранить пользователя');
        }
        $this->id = $id;

        return $id;
    }

    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM users WHERE id = $id";
        $data = $db->fetchOne($select);

        if (!$data) {
            return null;
        }

        return new self($data);
    }

    /**
     * Получить хэш для строки
     *
     * @param string $password
     * @return string
     */
    public static function getPasswordHash(string $password): string
    {
        return sha1('loftschool-' . $password);
    }
}
