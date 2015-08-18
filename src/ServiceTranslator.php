<?php
namespace Slince\Applicaion;

use Slince\Di\Container;
use Slince\Di\Definition;

class ServiceTranslator implements ServiceTranslatorInterface
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
        }
        if (is_array($config)) {
            $this->_container->setDefinition($name, new Definition([
                $config['class'],
                $config['arguments'],
                $config['methodCalls']
            ]), $this->_shared);
        }
        return $this;
    }

    function initializeFromConfigArray(array $configs)
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