<?php
use Slince\Application\EventStore;
use Slince\Event\Event;

return [
    'debug' => false,
    'rootPath' => ROOT_PATH,
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
];