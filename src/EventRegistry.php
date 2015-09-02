<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Event\Event;
use Slince\Event\ListenerInterface;

class EventRegistry
{

    /**
     *
     * Application
     *
     * @var AbstractApplication
     */
    protected $_application;

    protected $_loaded = [];

    function __construct(ApplicationInterface $application)
    {
        $this->_application = $application;
    }
    
    function _bindSystemListeners()
    {
        
    }

    function register($objectName)
    {
        $listener = $this->load($objectName);
        if ($listener instanceof ListenerInterface) {
            $this->_application->getDispatcher()->addListener($eventName, $listener);
        }
        $this->_application->getDispatcher()->addSubscriber();
        return $this;
    }

    function load($objectName)
    {
        if (isset($this->_loaded[$objectName])) {
            return $this->_loaded[$objectName];
        }
        $class = $this->_resolveClassName($objectName);
        $instance = new $class();
        $this->_loaded[$objectName] = $instance;
        return $instance;
    }

    function _resolveClassName($objectName)
    {
        return '';
    }
}