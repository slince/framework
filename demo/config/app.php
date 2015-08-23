<?php
use Slince\Application\EventStore;
use Slince\Event\Event;

return [
    'app' => [
        'debug' => false,
        'root' => dirname(__DIR__),
        'timezone' => 'Asia/shanghai',
        'locale' => 'zh_cn',
        'listeners' => [
            EventStore::PROCESS_REQUEST => function(Event $event) {
                $routes = $event->getSubject()->getRouter()->getRoutes();
                $routeCreateCallback = include __DIR__ . '/routes.php';
                call_user_func($routeCreateCallback, $routes);
            }
        ]
    ]
];