<?php
use Slince\Config;
use Slince\Config\Parser;

include __DIR__ . '/path.php';
include CONFIG_PATH . 'route.php';

$config = new Repository();
$config->merge(new PhpArrayParser(CONFIG_PATH . 'app.php'));
/**
 *继续合并自定义的配置文件
 *$config->merge(new PhpArrayParser(CONFIG_PATH . 'custom.php'));
 */

return $config;