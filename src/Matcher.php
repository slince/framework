<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

use Slince\Routing\Exception\RouteNotFoundException;
use Slince\Routing\Exception\MethodNotAllowedException;

class Matcher implements MatcherInterface
{

    /**
     * request context
     *
     * @var RequestContext
     */
    protected $_context;

    /**
     * require methods
     *
     * @var array
     */
    protected $_requiredMethods;

    function __construct(RequestContext $context)
    {
        $this->_context = $context;
    }

    function macthHost(RouteInterface $route)
    {
        if (is_null($route->getHostRegex())) {
            return true;
        }
        if (preg_match($route->getHostRegex(), $this->_context->getHost(), $matches)) {
            $route->setParameter('_hostMatches', $matches);
            return true;
        }
        return false;
    }

    function matchMethod(RouteInterface $route)
    {
        if (empty($route->getMethods())) {
            return true;
        }
        return is_array($this->_context->getMethod(), $route->getMethods());
    }

    function matchSchema(RouteInterface $route)
    {
        if (empty($route->getSchemes())) {
            return true;
        }
        return is_array($this->_context->getScheme(), $route->getSchemes());
    }

    function matchPath(RouteInterface $route)
    {
        if (is_null($route->getPathRegex())) {
            return true;
        }
        if (preg_match($route->getPathRegex(), rawurldecode($context->getPathInfo()), $matches)) {
            $route->setParameter('_pathMatches', $matches);
            return true;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Routing\MatcherInterface::match()
     */
    function match($path, RouteCollection $routes)
    {
        $path = '/' . ltrim($path, '/');
        $this->_requireMethods = [];
        $this->_context->setPathInfo($path);
        // 查找符合条件的route
        foreach ($routes as $route) {
            $result = $this->matchSchema($route) && $this->macthHost($route)
                && $this->matchPath($route); 
            if ($result) {
                if ($this->matchMethod($route)) {
                    $routeParameters = $this->_getRouteParameters($route);
                    $route->setRouteParameters($routeParameters);
                    return $route;
                } else {
                    $this->_requireMethods += $route->getMethods();
                }
            }
        }
        if (empty($this->_requiredMethods)) {
            throw new MethodNotAllowedException($this->_requiredMethods);
        }
        throw new RouteNotFoundException();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Routing\MatcherInterface::setContext()
     */
    function setContext(RequestContext $context)
    {
        $this->_context = $context;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Routing\MatcherInterface::getContext()
     */
    function getContext()
    {
        return $this->_context;
    }

    /**
     * 处理路由参数
     *
     * @param RouteInterface $route            
     * @return array
     */
    protected function _getRouteParameters(RouteInterface $route)
    {
        return array_replace($route->getDefaults(), 
            $route->getParameters('_hostMatches', []), 
            $route->getParameters('_pathMatches', [])
        );
    }
}