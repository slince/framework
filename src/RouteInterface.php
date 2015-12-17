<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

interface RouteInterface
{

    /**
     * 设置path
     *
     * @param string $path
     *            path
     * @return RouteInterface
     */
    function setPath($path);

    /**
     * get path
     *
     * @return string path
     */
    function getPath();
    
    /**
     * get path regex
     * @return string
     */
    function getPathRegex();

    /**
     * 设置parameter
     *
     * @param string $name            
     * @param mixed $parameter            
     * @return RouteInterface
     */
    function setParameter($name, $parameter);

    /**
     * 获取parameter
     *
     * @param string $name            
     * @param string $default            
     * @return RouteInterface
     */
    function getParameter($name, $default = null);

    /**
     * 是否存在参数
     * 
     * @param string $name
     * @return mixed
     */
    function hasParameter($name);
    
    /**
     * 设置parameters
     *
     * @param array $parameters            
     * @return RouteInterface
     */
    function setParameters(array $parameters);

    /**
     * 获取parameters
     *
     * @return array
     */
    function getParameters();

    /**
     * 设置requirements
     *
     * @param array $requirements            
     * @return RouteInterface
     */
    function setRequirements(array $requirements);

    /**
     * 单个设置requirement
     *
     * @param string $name            
     * @param string $requirement            
     * @return RouteInterface
     */
    function setRequirement($name, $requirement);

    /**
     * 获取requirements
     *
     * @return array
     */
    function getRequirements();

    /**
     * 添加requirements
     *
     * @param array $requirements            
     * @return RouteInterface
     */
    function addRequirements(array $requirements);

    /**
     * 获取requirement
     *
     * @param string $name            
     * @param string $default            
     * @return string
     */
    function getRequirement($name, $default = null);

    /**
     * 设置schemes
     *
     * @param array $schemes            
     * @return RouteInterface
     */
    function setSchemes(array $schemes);

    /**
     * 获取schemes
     *
     * @return array
     */
    function getSchemes();

    /**
     * 设置methods
     *
     * @param array $methods            
     * @return RouteInterface
     */
    function setMethods(array $methods);

    /**
     * 获取method
     *
     * @return array
     */
    function getMethods();

    /**
     * 设置host
     *
     * @param string $host            
     * @return RouteInterface
     */
    function setHost($host);

    /**
     * 获取host
     *
     * @return array
     */
    function getHost();
    
    /**
     * get host regex
     * 
     * @return string
     */
    function getHostRegex();

    /**
     * 设置处理之后的路由参数
     *
     * @param array $parameters            
     * @return RouteInterface
     */
    function setRouteParameters(array $parameters);

    /**
     * 获取路由参数
     *
     * @return array
     */
    function getRouteParameters();
}