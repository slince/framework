<?php
use Slince\Config\Repository;
use Slince\Config\File\PhpFile;
use Slince\Application\EventStore;
use Slince\Event\Event;
use Cake\Datasource\ConnectionManager;
use Slince\Application\WebApplication;
use Symfony\Component\HttpFoundation\Request;

include __DIR__ . '/../../vendor/autoload.php';
$config = Repository::newInstance();
$config->merge(new PhpFile(__DIR__ . '/app.php'));
$config->merge(new PhpFile(__DIR__ . '/services.php'), 'service');

$request = Request::createFromGlobals();
$webApp = new WebApplication($config, $request);
$webApp->getDispatcher()->bind(EventStore::APP_INIT, function (Event $event)
{
    $app = $event->getSubject();
    $config = $app->getConfig();
    foreach ($config['db'] as $name => $config) {
        ConnectionManager::config($name, $config['default']);
    }
});
