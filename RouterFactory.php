<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

class RouterFactory
{

    /**
     * 创建Matcher
     * @param RequestContext|null $context
     * @return Router
     */
    static function create(RequestContext $context = null)
    {
        $routes = Factory::createRoutes();
        return new Router($routes, $context);
    }
}