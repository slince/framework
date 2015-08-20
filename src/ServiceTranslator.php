<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Di\Container;
use Slince\Di\Definition;
use Slince\Applicaion\LogicException;

class ServiceTranslator
{

    protected $_container;
    
    protected $_shared = true;

    function __construct(Container $container)
    {
        $this->_container = $container;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Applicaion\ServiceTranslatorInterface::translate()
     */
    function translate($name, $config)
    {
        if (is_callable($config)) {
            $this->_container->set($name, $config, $this->_shared);
        } elseif (is_array($config)) {
            $shared = isset($config['shared']) ? (bool)$config['shared'] : $this->_shared;
            if(isset($config['create']) && is_callable($config['create'])) {
                $this->_container->set($name, $config, $shared);
            } else {
                //配置数组中至少要有键值'class'
                if (! isset($config['class'])) {
                    throw new LogicException('');
                }
                if (! isset($config['arguments'])) {
                    $config['arguments'] = [];
                }
                if (! isset($config['methodCalls'])) {
                    $config['methodCalls'] = [];
                }
                $this->_container->setDefinition($name, new Definition([
                    $config['class'],
                    $config['arguments'],
                    $config['methodCalls']
                ]), $shared);
            }
        }
        return $this;
    }

    function initializeFromArray(array $configs)
    {
        foreach ($configs as $serviceName => $config) {
            $this->translate($serviceName, $config);
        }
    }
    
    function setShared($enabled)
    {
        $this->_shared = $enabled;
    }
    
    function getShared()
    {
        return $this->_shared;
    }
}