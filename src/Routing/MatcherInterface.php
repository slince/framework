<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

interface MatcherInterface
{

    /**
     * 查找匹配的route
     *
     * @param string $path
     * @param RouteCollection $routeCollection
     * @return RouteInterface
     */
    function match($path, RouteCollection $routeCollection);

    /**
     * 设置上下文
     *
     * @param RequestContext $context
     */
    function setContext(RequestContext $context);

    /**
     * 获取上下文
     *
     * @return RequestContext $context
     */
    function getContext();
}