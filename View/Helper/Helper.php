<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Helper;

use Slince\View\ViewManager;

abstract class Helper implements HelperInterface
{
    protected $name;
    
    /**
     * 
     * @var ViewManager
     */
    protected $viewManager;
    
    function __construct(ViewManager $viewManager = null)
    {
        $this->viewManager = $viewManager;
    }
    function getName()
    {
        return $this->name;
    }
    
    function setName($name)
    {
        $this->name = $name;
    }
    
    function setViewManager(ViewManager $viewManager)
    {
        $this->viewManager = $viewManager;
    }
    
    function getViewManager()
    {
        return $this->viewManager;
    }
}