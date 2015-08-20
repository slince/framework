<?php
use Slince\Config\Repository;
use Slince\Config\File\PhpFile;

include __DIR__ . '/../../vendor/autoload.php';
$config = Repository::newInstance();
$config->merge(new PhpFile(__DIR__ . '/app.php'));
$config->merge(new PhpFile(__DIR__ . '/services.php'));
return $config;