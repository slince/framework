<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

class Factory
{

    /**
     * 创建Matcher
     * 
     * @param RequestContext $context
     * @return Matcher
     */
    static function createMatcher(RequestContext $context = null)
    {
        return new Matcher($context);
    }

    /**
     * 创建Generator
     * @param RequestContext $context
     * @return Generator
     */
    static function createGenerator(RequestContext $context)
    {
        return new Generator($context);
    }

    /**
     * 创建route collection
     *
     * @param array $routes            
     * @return RouteCollection
     */
    static function createRoutes($routes = [])
    {
        return new RouteCollection($routes);
    }
}