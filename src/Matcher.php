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

    function __construct(RequestContext $context = null)
    {
        $this->_context = $context;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Routing\MatcherInterface::match()
     */
    function match($path, RouteCollection $routes)
    {
        $path = '/' . ltrim($path, '/');
        if (is_null($this->_context)) {
            $route = $this->_findRouteWithoutRequestContext($path, $routes);
        } else {
            $route = $this->_findRouteWithRequestContext($path, $routes);
        }
        return $route;
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
     * 找出匹配path的route
     *
     * @param string $path            
     * @param RouteCollection $routes            
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     * @return RouteInterface
     */
    protected function _findRouteWithRequestContext($path, RouteCollection $routes)
    {
        $requireMethods = [];
        // 查找符合条件的route
        foreach ($routes as $route) {
            if ($this->_matchSchema($route) && $this->_macthHost($route) && $this->_matchPath($path, $route)) {
                if ($this->_matchMethod($route)) {
                    $routeParameters = $this->_getRouteParameters($route);
                    $route->setRouteParameters($routeParameters);
                    return $route;
                } else {
                    $requireMethods += $route->getMethods();
                }
            }
        }
        if (! empty($requireMethods)) {
            throw new MethodNotAllowedException($requireMethods);
        }
        throw new RouteNotFoundException();
    }

    /**
     * 找出匹配path的route，不考虑request上下文
     *
     * @param string $path            
     * @param RouteCollection $routes            
     * @throws RouteNotFoundException
     * @return RouteInterface
     */
    protected function _findRouteWithoutRequestContext($path, RouteCollection $routes)
    {
        foreach ($routes as $route) {
            if ($this->_matchPath($path, $route)) {
                return $route;
            }
        }
        throw new RouteNotFoundException();
    }

    protected function _macthHost(RouteInterface $route)
    {
        if (is_null($route->compile()->getHostRegex())) {
            return true;
        }
        if (preg_match($route->getHostRegex(), $this->_context->getHost(), $matches)) {
            $routeParameters = array_intersect_key($matches, array_flip($route->getVariables()));
            $route->setParameter('_hostMatches', $routeParameters);
            return true;
        }
        return false;
    }

    protected function _matchMethod(RouteInterface $route)
    {
        if (empty($route->getMethods())) {
            return true;
        }
        return is_array($this->_context->getMethod(), $route->getMethods());
    }

    protected function _matchSchema(RouteInterface $route)
    {
        if (empty($route->getSchemes())) {
            return true;
        }
        return is_array($this->_context->getScheme(), $route->getSchemes());
    }

    protected function _matchPath($path, RouteInterface $route)
    {
        if (is_null($route->compile()->getPathRegex())) {
            return true;
        }
        if (preg_match($route->getPathRegex(), rawurldecode($path), $matches)) {
            $routeParameters = array_intersect_key($matches, array_flip($route->getVariables()));
            $route->setParameter('_pathMatches', $routeParameters);
            return true;
        }
        return false;
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