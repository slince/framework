<?php
use Slince\Config\Repository;
use Slince\Config\File\PhpFile;
use Slince\Application\WebApplication;

include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/paths.php';

$config = Repository::newInstance();
$config->merge(new PhpFile(__DIR__ . '/app.php'));
$config->merge(new PhpFile(__DIR__ . '/services.php'), 'service');

$webApp = new WebApplication($config);