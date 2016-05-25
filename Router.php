<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

use Slince\Routing\Exception\RouteNotFoundException;

class Router
{

    /**
     * routes
     *
     * @var RouteCollection
     */
    protected $_routes;

    /**
     * matcher
     *
     * @var MatcherInterface
     */
    protected $_matcher;

    /**
     * generator
     *
     * @var GeneratorInterface
     */
    protected $_generator;

    /**
     * request context
     *
     * @var RequestContext
     */
    protected $_context;

    function __construct(RouteCollection $routes, RequestContext $context = null)
    {
        $this->_routes = $routes;
        $this->_context = $context;
    }

    /**
     * 匹配给定的路径
     *
     * @param string $path
     * @return RouteInterface
     */
    function match($path)
    {
        $route = $this->getMatcher()->match($path, $this->_routes);
        return $route;
    }

    /**
     * 生成特定路由的url
     *
     * @param RouteInterface $route
     * @param array $parameters
     * @param boolean $absolute
     * @return string
     */
    function generate(RouteInterface $route, $parameters = [], $absolute = false)
    {
        return $this->getGenerator()->generate($route, $parameters, $absolute);
    }

    /**
     * 根据route name生成url
     *
     * @param string $name
     * @param array $parameters
     * @param boolean $absolute
     * @return string
     */
    function generateByName($name, $parameters = [], $absolute = false)
    {
        $route = $this->_routes->getByName($name);
        if (is_null($route)) {
            throw new RouteNotFoundException(sprintf('Route "%s" not defined.', $name));
        }
        return $this->getGenerator()->generate($route, $parameters, $absolute);
    }

    /**
     * 根据action生成url
     *
     * @param string $action
     * @param array $parameters
     * @param boolean $absolute
     * @return string
     */
    function generateByAction($action, $parameters = [], $absolute = false)
    {
        $route = $this->_routes->getByAction($action);
        if (is_null($route)) {
            throw new RouteNotFoundException(sprintf('Action "%s" not defined.', $action));
        }
        return $this->getGenerator()->generate($route, $parameters, $absolute);
    }

    /**
     * 获取routes
     *
     * @return \Slince\Routing\RouteCollection
     */
    function getRoutes()
    {
        return $this->_routes;
    }

    /**
     * 获取matcher
     *
     * @return \Slince\Routing\MatcherInterface
     */
    function getMatcher()
    {
        if (is_null($this->_matcher)) {
            $this->_matcher = Factory::createMatcher($this->_context);
        }
        return $this->_matcher;
    }

    /**
     * 获取generator
     *
     * @return \Slince\Routing\GeneratorInterface
     */
    function getGenerator()
    {
        if (is_null($this->_generator)) {
            $this->_generator = Factory::createGenerator($this->_context);
        }
        return $this->_generator;
    }

    /**
     * 设置上下文
     *
     * @param RequestContext $context
     */
    function setContext(RequestContext $context)
    {
        $this->_context = $context;
    }

    /**
     * 获取上下文
     *
     * @return RequestContext $context
     */
    function getContext()
    {
        return $this->_context;
    }
}