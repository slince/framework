<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

use Slince\View\Helper\HelperInterface;
use Slince\View\Exception\InvalidArgumentException;

abstract class ViewManager
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
     * 设置视图位置
     * 
     * @param string $path            
     */
    function setViewPath($path)
    {
        $this->_viewPath = rtrim($path, '/') . DIRECTORY_SEPARATOR;
    }

    /**
     * 返回视图位置
     */
    function getViewPath()
    {
        return $this->_viewPath;
    }

    /**
     * 设置布局文件位置
     * 
     * @param string $path            
     */
    function setLayoutPath($path)
    {
        $this->_layoutPath = rtrim($path, '/') . DIRECTORY_SEPARATOR;
    }

    /**
     * 返回布局位置
     */
    function getLayoutPath()
    {
        return $this->_layoutPath;
    }
    
    /**
     * 注册一个helper class
     * 
     * @param string $name
     * @param string $class
     */
    function registerHelperClass($name, $class)
    {
        $this->helperClasses[$name] = $class;
    }
    
    /**
     * 注册一组helper class
     *
     * @param array $classes
     */
    function registerHelperClasses($classes)
    {
        foreach ($classes as $name => $class) {
            $this->registerHelperClass($name, $class);
        }
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
        return $this->helpers[$name] = new $this->helperClasses[$name]($this);
    }
}