<?php
return [
    'debug' => false,
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
            'quoteIdentifiers' => false
        ]
    ],
    'cache' => [
        '_cake_core_' => [
            'className' => 'File',
            'prefix' => 'myapp_cake_core_',
            'path' => CACHE_PATH . '/',
            'serialize' => true,
            'duration' => '+2 minutes'
        ],
        '_cake_model_' => [
            'className' => 'File',
            'prefix' => 'myapp_cake_model_',
            'path' => CACHE_PATH . 'models/',
            'serialize' => true,
            'duration' => '+2 minutes',
        ],
    ],
];