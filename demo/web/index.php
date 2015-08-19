<?php
use Slince\Application\WebApplication;

$config = include __DIR__ . '../config/init.php';
$app = new WebApplication($config);
$app->run();
