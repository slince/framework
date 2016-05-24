<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Di\Container;

abstract class Bridge
{
    /**
     *  桥名称
     * @var string
     */
    protected $name;

    /**
     *  container
     * @var Container
     */
    protected $container;

    function getContainer()
    {
        return $this->container;
    }

    public function initialize(Container $container)
    {
        $this->container = $container;
    }

    public function shutdown()
    {
    }

    function getName()
    {
        if (is_null($this->name)) {
            $this->name = strrchr(get_class($this), '\\');
        }
        return $this->name;
    }

    /**
     *  获取用户为bridge设置的配置参数
     * @return array
     */
    public function getConfigs()
    {
        return $this->getContainer()->get('config')->get($this->getName(), []);
    }
}