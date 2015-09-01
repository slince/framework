<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Bridge;

use Slince\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Cache\Cache;
use Cake\Core\Configure;

class CakeBridge extends Bridge
{

    function init(Event $event)
    {
        $app = $event->getSubject();
        $configs = $app->getConfig()->getDataObject();
        // 设置数据库
        foreach ($configs['datasources'] as $name => $config) {
            ConnectionManager::config($name, $config);
        }
        // 设置缓存
        foreach ($configs['cache'] as $name => $config) {
            Cache::config($name, $config);
        }
        //设置config
        Configure::write('App.namespace', 'App');
    }
}