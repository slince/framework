<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

class ListenerRegistry
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
    
    function register($objectName)
    {
        $this->_application->getDispatcher()
            ->addSubscriber($this->load($objectName));
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
        
    }
}