<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

class RouterFactory
{

    /**
     * 创建一个routing
     *
     * @param RequestContext $context            
     * @return Router
     */
    static function create(RequestContext $context = null)
    {
        if (is_null($context)) {
            $context = RequestContext::create();
        }
        return new Router(RouteCollection::create(), new Matcher($context), new Generator($context), $context);
    }
}