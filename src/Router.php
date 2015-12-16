<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

use Slince\Router\GeneratorInterface;
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

    function __construct(RouteCollection $routes, MatcherInterface $matcher, GeneratorInterface $generator = null, RequestContext $context)
    {
        $this->_routes = $routes;
        $this->_matcher = $matcher;
        $this->_generator = $generator;
        $this->_context = $context;
        $this->_matcher->setContext($context);
        $this->_generator->setContext($context);
    }

    /**
     * 生成特定路由的url
     *
     * @param RouteInterface $route            
     * @param array $parameters            
     * @param boolean $absolute            
     * @return string
     */
    function match($path)
    {
        $route = $this->_matcher->match($path, $this->_routes);
        return $route;
    }

    /**
     * 生成一个路径
     *
     * @param Route $route            
     */
    function generate(RouteInterface $route, $parameters = [], $absolute = true)
    {
        return $this->_generator->generate($route, $parameters, $absolute);
    }

    /**
     * 根据route name生成url
     *
     * @param string $name            
     * @param array $parameters            
     * @param boolean $absolute            
     * @return string
     */
    function generateByName($name, $parameters = [], $absolute = true)
    {
        return $this->_generator->generateByName($name, $parameters, $absolute);
    }

    /**
     * 根据action生成url
     *
     * @param string $action            
     * @param array $parameters            
     * @param boolean $absolute            
     * @return string
     */
    function generateByAction($action, $parameters = [], $absolute = true)
    {
        return $this->_generator->generateByAction($action, $parameters, $absolute);
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
        return $this->_matcher;
    }

    /**
     * 获取generator
     *
     * @return \Slince\Routing\GeneratorInterface
     */
    function getGenerator()
    {
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