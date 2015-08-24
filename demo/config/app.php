<?php
use Slince\Application\EventStore;
use Slince\Event\Event;

return [
    'app' => [
        'debug' => false,
        'root' => dirname(__DIR__),
        'timezone' => 'Asia/shanghai',
        'locale' => 'zh_cn',
        'datasources' => [
            'default' => [
                'className' => 'Cake\Database\Connection',
                'driver' => 'Cake\Database\Driver\Mysql',
                'persistent' => false,
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'slcms',
                'encoding' => 'utf8',
                'timezone' => '+8:00',
                'cacheMetadata' => true,
                'quoteIdentifiers' => false,
            ]
        ],
        'listeners' => [
            EventStore::PROCESS_REQUEST => function(Event $event) {
                $routes = $event->getSubject()->getRouter()->getRoutes();
                $routeCreateCallback = include __DIR__ . '/routes.php';
                call_user_func($routeCreateCallback, $routes);
            }
        ]
    ]
];