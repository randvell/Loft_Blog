<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 22.01.2022
 * Time: 15:18
 */

namespace Core;

class Config
{
    public const DB_HOST = '127.0.0.1';
    public const DB_NAME = 'loftschool';
    public const DB_USERNAME = 'loftschool';
    public const DB_PASSWORD = 'loftschool';

    public const DB_PDO = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME;
}
