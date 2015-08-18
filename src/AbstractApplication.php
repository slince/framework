<?php
namespace Slince\Applicaion;

use Slince\Config\Repository;
use Slince\Di\Container;
use Slince\Event\Dispatcher;

abstract class AbstractApplication implements ApplicationInterface
{

    /**
     * 项目主要部分文件所在位置
     * 
     * @var string
     */
    protected $_src;

    /**
     * Container instance
     *
     * @var Container
     */
    protected $_container;

    /**
     * dispatcher instance
     *
     * @var Dispatcher
     */
    protected $_dispatcher;

    /**
     * config instance
     *
     * @var Repository
     */
    protected $_config;

    function __construct(Repository $config)
    {
        $this->_config = $config;
        $this->_kernelInit();
    }

    /**
     * 获取组件
     * 
     * @param unknown $name            
     * @return Ambigous <object, multitype:, mixed>
     */
    function get($name)
    {
        return $this->_container->get($key);
    }

    function getContainer()
    {
        return $this->_container;
    }

    function getConfig()
    {
        return $this->_config;
    }

    /**
     * 核心服务实例化
     */
    protected function _kernelInit()
    {
        $this->_container = KernelServiceFactory::createContainer();
        $this->_dispatcher = KernelServiceFactory::createDispatcher();
        $this->_dispatcher->dispatch(EventStore::KERNEL_INITED);
    }
}