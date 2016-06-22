<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

use Slince\Routing\Exception\InvalidArgumentException;
use Slince\Routing\Exception\RouteNotFoundException;

class Router
{
    /**
     * routes
     * @var RouteCollection
     */
    protected $routes;

    /**
     * matcher
     * @var MatcherInterface
     */
    protected $matcher;

    /**
     * generator
     * @var GeneratorInterface
     */
    protected $generator;

    /**
     * request context
     * @var RequestContext
     */
    protected $context;

    /**
     * @var RouteBuilder
     */
    protected $routeBuilder;

    function __construct(RouteCollection $routes, RequestContext $context = null)
    {
        $this->routes = $routes;
        $this->context = $context;
        $this->routeBuilder = new RouteBuilder('/', $routes);
    }

    /**
     * 匹配给定的路径
     * @param string $path
     * @return RouteInterface
     */
    function match($path)
    {
        $route = $this->getMatcher()->match($path, $this->routes);
        return $route;
    }

    /**
     * 生成特定路由的url
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
     * @param $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     * @throws RouteNotFoundException
     */
    function generateByName($name, $parameters = [], $absolute = false)
    {
        $route = $this->routes->getByName($name);
        if (is_null($route)) {
            throw new RouteNotFoundException(sprintf('Route "%s" not defined.', $name));
        }
        return $this->getGenerator()->generate($route, $parameters, $absolute);
    }

    /**
     * 根据action生成url
     * @param $action
     * @param array $parameters
     * @param bool $absolute
     * @return string
     * @throws RouteNotFoundException
     */
    function generateByAction($action, $parameters = [], $absolute = false)
    {
        $route = $this->routes->getByAction($action);
        if (is_null($route)) {
            throw new RouteNotFoundException(sprintf('Action "%s" not defined.', $action));
        }
        return $this->getGenerator()->generate($route, $parameters, $absolute);
    }

    /**
     * 获取routes
     * @return RouteCollection
     */
    function getRoutes()
    {
        return $this->routes;
    }

    /**
     * 获取matcher
     * @return Matcher|MatcherInterface
     */
    function getMatcher()
    {
        if (is_null($this->matcher)) {
            $this->matcher = Factory::createMatcher($this->context);
        }
        return $this->matcher;
    }


    /**
     * 获取generator
     * @return Generator|GeneratorInterface
     */
    function getGenerator()
    {
        if (is_null($this->generator)) {
            if (is_null($this->context)) {
                throw new InvalidArgumentException('Miss Argument "Context" for generator');
            }
            $this->generator = Factory::createGenerator($this->context);
        }
        return $this->generator;
    }

    /**
     * 设置上下文
     * @param RequestContext $context
     */
    function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * 获取上下文
     * @return RequestContext $context
     */
    function getContext()
    {
        return $this->context;
    }

    /**
     * @return RouteBuilder
     */
    public function getRouteBuilder()
    {
        return $this->routeBuilder;
    }
}