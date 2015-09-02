<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

class ListenerRegistry
{
    protected $_application;
    
    function __construct(ApplicationInterface $application)
    {
        $this->_application = $application;
    }
    
    function register()
    {
        
    }
}