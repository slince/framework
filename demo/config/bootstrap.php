<?php
use Slince\Config\Repository;
use Slince\Config\File\PhpFile;
use Slince\Application\EventStore;
use Slince\Event\Event;
use Cake\Datasource\ConnectionManager;
use Slince\Application\WebApplication;
use Cake\Cache\Cache;

include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/paths.php';

$config = Repository::newInstance();
$config->merge(new PhpFile(__DIR__ . '/app.php'));
$config->merge(new PhpFile(__DIR__ . '/services.php'), 'service');

$webApp = new WebApplication($config);
//app初始化完成之后，初始化配置
$webApp->getDispatcher()->bind(EventStore::APP_INITED, function (Event $event)
{
    $app = $event->getSubject();
    $configs = $app->getConfig()->getDataObject();
    //设置数据库
    foreach ($configs['datasources'] as $name => $config) {
        ConnectionManager::config($name, $config);
    }
    //设置缓存
    foreach ($configs['cache'] as $name => $config) {
        Cache::config($name, $config);
    }
});
