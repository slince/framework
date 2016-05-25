<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

interface GeneratorInterface
{

    /**
     * 生成特定路由的url
     *
     * @param RouteInterface $route
     * @param array $parameters
     * @param boolean $absolute
     * @return string
     */
    function generate(RouteInterface $route, $parameters = [], $absolute = false);

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