<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 22.01.2022
 * Time: 14:26
 */

namespace Core;

use PDO;
use PDOStatement;
use RuntimeException;
use Throwable;

require_once 'Config.php';

class Db
{
    private static ?Db $instance = null;
    private ?PDO $pdo = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * Получить инстанс БД
     *
     * @return Db
     */
    public static function getInstance(): Db
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Получить соединение
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connect();
    }

    /**
     * Выполнить подключение к БД
     *
     * @return PDO
     */
    private function connect(): PDO
    {
        if (!$this->pdo) {
            try {
                $this->pdo = new PDO(Config::DB_PDO, Config::DB_USERNAME, Config::DB_PASSWORD);
            } catch (Throwable $e) {
                throw new RuntimeException('Не удалось установить соединение с БД:' . $e->getMessage());
            }
        }

        return $this->pdo;
    }

    /**
     * Выполнить запрос БД
     *
     * @param string $query
     * @param array $params
     *
     * @return PDOStatement
     */
    public function exec(string $query, array $params = []): PDOStatement
    {
        $pdo = $this->getConnection();
        $request = $pdo->prepare($query);
        $result = $request->execute($params);

        if (!$result && $request->errorCode()) {
            throw new RuntimeException($request->errorInfo());
        }

        return $request;
    }

    /**
     * Получить данные по запросу
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function fetchAll(string $query, array $params = []): array
    {
        return $this
            ->exec($query, $params)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получить одну запись по запросу
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function fetchOne(string $query, array $params = []): array
    {
        $array = $this->fetchAll($query, $params);
        if (!$array) {
            return [];
        }

        return reset($array);
    }

    /**
     * Получить PK последней записи
     *
     * @return int|null
     */
    public function getLastInsertId(): ?int
    {
        return (int)$this->getConnection()->lastInsertId() ?: null;
    }
}
