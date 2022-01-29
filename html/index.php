<?php
/**
 * Created by PhpStorm.
 * User: Nikita Zimin
 * Date: 28.01.2022
 * Time: 18:50
 */

include '../vendor/autoload.php';
include '../src/config.php';

$app = new \Core\Application();
$app->run();
