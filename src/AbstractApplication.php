<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Config\Repository;
use Slince\Di\Container;
use Slince\Event\Dispatcher;
use Slince\Event\Event;
use Slince\Application\Exception\LogicException;

abstract class AbstractApplication implements ApplicationInterface
{

    /**
     * app根文件位置
     * 
     * @var string
     */
    protected $_root;
    
    /**
     * 项目主要部分文件所在位置
     * 
     * @var string
     */
    protected $_src;

    /**
     * Container instance
     *
     * @var ServiceTranslator
     */
    protected $_serviceTranslator;
    
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
     * Config repository instance
     *
     * @var Repository
     */
    protected $_config;
    
    protected $_parameters;

    function __construct(Repository $config)
    {
        $this->_config = $config;
        $this->_initializeKernel();
        $this->_initalizeApplication();
    }

    function getContainer()
    {
        return $this->_container;
    }

    function getConfig()
    {
        return $this->_config;
    }

    function setParameter($name, $parameter)
    {
        $this->_parameters[$name] = $parameter;
        return $this;
    }

    function getParameter($name, $default = null)
    {
        return isset($this->_parameters[$name]) ? $this->_parameters[$name] : $default;
    }

    function hasParameter($name)
    {
        return isset($this->_parameters[$name]);
    }

    function setParameters(array $parameters)
    {
        $this->_parameters = $parameters;
        return $this;
    }

    function getParameters()
    {
        return $this->_parameters;
    }
    /**
     * 核心服务实例化
     */
    protected function _initializeKernel()
    {
        $this->_container = KernelServiceFactory::createContainer();
        $this->_serviceTranslator = KernelServiceFactory::createServiceTranslator($this->_container);
        $this->_dispatcher = KernelServiceFactory::createDispatcher();
        $this->_container->share('app', $this);
        $this->_dispatchEvent(EventStore::KERNEL_INITED);
    }
    
    protected function _initalizeApplication()
    {
        $configs = $this->_config->getDataObject();
        //初始化app配置
        $this->_root = $configs['app']['root'];
        if (empty($this->_root)) {
            throw new LogicException("Application root path is unknow!");
        }
        //初始化事件监听器
        $this->_bindListeners($configs['app']['listeners']);
        //初始化服务配置文件
        $this->_serviceTranslator->initializeFromArray($configs->get('service', []));
        //error相关处理
        $this->_handleError();
    }
    
    protected function _dispatchEvent($eventName, $arguments = [])
    {
        $event = new Event($eventName, $this, $this->_dispatcher, $arguments);
        $this->_dispatcher->dispatch($eventName, $event);
    }
    
    function _bindListeners($listeners)
    {
        foreach ($listeners as $eventName => $listener) {
            $this->_dispatcher->bind($eventName, $listener);
        }
    }
    
    protected function _handleError()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}