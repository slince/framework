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
     * @param string $path
     * @return RouteInterface
     */
    function setPath($path);

    /**
     * 获取path
     * @return string path
     */
    function getPath();

    /**
     * 获取path regex
     * @return string
     */
    function getPathRegex();

    /**
     * 设置parameter
     * @param string $name
     * @param mixed $parameter
     * @return RouteInterface
     */
    function setParameter($name, $parameter);

    /**
     * 获取parameter
     * @param string $name
     * @param string $default
     * @return RouteInterface
     */
    function getParameter($name, $default = null);

    /**
     * 是否存在参数
     * @param string $name
     * @return mixed
     */
    function hasParameter($name);

    /**
     * 设置parameters
     * @param array $parameters
     * @return RouteInterface
     */
    function setParameters(array $parameters);

    /**
     * 获取parameters
     * @return array
     */
    function getParameters();

    /**
     * 设置requirements
     * @param array $requirements
     * @return RouteInterface
     */
    function setRequirements(array $requirements);

    /**
     * 设置单个requirement
     * @param string $name
     * @param string $requirement
     * @return RouteInterface
     */
    function setRequirement($name, $requirement);

    /**
     * 添加requirements
     * @param array $requirements
     * @return RouteInterface
     */
    function addRequirements(array $requirements);

    /**
     * 获取requirements
     * @return array
     */
    function getRequirements();

    /**
     * 获取requirement
     * @param string $name
     * @param string $default
     * @return string|null
     */
    function getRequirement($name, $default = null);

    /**
     * 设置schemes
     * @param array $schemes
     * @return RouteInterface
     */
    function setSchemes(array $schemes);

    /**
     * 获取schemes
     * @return array
     */
    function getSchemes();

    /**
     * 设置methods
     * @param array $methods
     * @return RouteInterface
     */
    function setMethods(array $methods);

    /**
     * 获取method
     * @return array
     */
    function getMethods();

    /**
     * 设置host
     * @param string $host
     * @return RouteInterface
     */
    function setHost($host);

    /**
     * 获取host
     * @return string
     */
    function getHost();

    /**
     * 获取host regex
     * @return string
     */
    function getHostRegex();
}