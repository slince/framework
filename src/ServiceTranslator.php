<?php
namespace Slince\Applicaion;

use Slince\Di\Container;
use Slince\Di\Definition;

class ServiceTranslator implements ServiceTranslatorInterface
{

    protected $_container;

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
            $this->_container->set($name, $config);
        }
        if (is_array($config)) {
            $this->_container->setDefinition($name, new Definition([
                $config['class'],
                $config['arguments'],
                $config['methodCalls']
            ]));
        }
        return $this;
    }

    function initializeFromConfigArray(array $configs)
    {
        foreach ($configs as $serviceName => $config) {
            $this->translate($serviceName, $config);
        }
    }
}