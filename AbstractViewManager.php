<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

use Slince\View\Helper\HelperInterface;
use Slince\View\Exception\InvalidArgumentException;

abstract class AbstractViewManager implements ViewManagerInterface
{

    /**
     * 视图文件位置
     * 
     * @var string
     */
    protected $_viewPath;

    /**
     * 布局文件位置
     *
     * @var string
     */
    protected $_layoutPath;

    protected $helperClasses = [];
    
    protected $helpers = [];
    /**
     * configs
     * 
     * @param array $configs
     */
    function __construct($configs = [])
    {
        foreach ($configs as $key => $config) {
            $property = "_{$key}";
            if (property_exists($this, $property)) {
                $this->$property = $config;
            }
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::setViewPath()
     */
    function setViewPath($path)
    {
        $this->_viewPath = rtrim($path, '/') . DIRECTORY_SEPARATOR;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::getViewPath()
     */
    function getViewPath()
    {
        return $this->_viewPath;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::setLayoutPath()
     */
    function setLayoutPath($path)
    {
        $this->_layoutPath = rtrim($path, '/') . DIRECTORY_SEPARATOR;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::getLayoutPath()
     */
    function getLayoutPath()
    {
        return $this->_layoutPath;
    }
    
    function registerHelperClass($name, $class)
    {
        $this->helperClasses[$name] = $class;
    }
    
    /**
     * 获取helper
     * 
     * @param string $name
     * @throws InvalidArgumentException
     * @return HelperInterface
     */
    function getHelper($name)
    {
        if (isset($this->helpers[$name])) {
            return $this->helpers[$name];
        }
        if (! isset($this->helperClasses[$name])) {
            throw new InvalidArgumentException(sprintf('The helper "%s" is unknow', $name));
        }
        return $this->helpers[$name] = new $this->helperClasses[$name];
    }
}