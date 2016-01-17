<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Subscriber;

use Slince\Event\Event;
use Slince\Event\SubscriberInterface;
use Slince\Application\EventStore;
use Cake\Datasource\ConnectionManager;
use Cake\Cache\Cache;
use Cake\Core\Configure;

/**
 * 项目使用部分cake组件
 * cake组件配置工作采用订阅者方式
 */
class CakeSubscriber implements SubscriberInterface
{

    function getEvents()
    {
        return [
            EventStore::KERNEL_INITED => 'init'
        ];
    }

    function init(Event $event)
    {
        $kernel = $event->getSubject();
        $configs = $kernel->getContainer()->get('config');
        // 设置数据库
        foreach ($configs['datasources'] as $name => $config) {
            ConnectionManager::config($name, $config);
        }
        // 设置缓存
        foreach ($configs['cache'] as $name => $config) {
            Cache::config($name, $config);
        }
    }
}