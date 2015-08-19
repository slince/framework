<?php
use Slince\Config\Repository;
use Slince\Config\Parser\PhpArrayParser;
include __DIR__ . '/paths.php';
$config = Repository::newInstance();
$config->merge(new PhpArrayParser(CONFIG . 'app.php'));
$config->merge(new PhpArrayParser(CONFIG . 'services.php'), 'services');
return $config;