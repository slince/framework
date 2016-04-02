<?php
/**
 * slince cake bridge
 *
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakeBridge;

use Slince\Application\Bridge;
use Slince\Di\Container;
use Cake\Datasource\ConnectionManager;
use Cake\Cache\Cache;

class CakeBridge extends Bridge
{

    protected $name = 'db';

    function initialize(Container $container)
    {
        parent::initialize($container);
        list($datasources, $cache) = $this->getConfigs();
        // 设置数据库
        foreach ($datasources as $name => $config) {
            ConnectionManager::config($name, $config);
        }
        // 设置缓存
        foreach ($cache as $name => $config) {
            Cache::config($name, $config);
        }
    }
}