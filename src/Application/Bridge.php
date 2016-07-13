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

    /**
     * 获取服务容器
     * @return Container
     */
    function getContainer()
    {
        return $this->container;
    }

    /**
     * 初始化
     * @param Container $container
     */
    public function initialize(Container $container)
    {
        $this->container = $container;
    }

    public function shutdown()
    {
    }

    /**
     * 名称
     * @return string
     */
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