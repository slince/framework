<?php
/**
 * slince router library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Router;

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
    function generate(RouteInterface $route, $parameters = [], $absolute = true);

    /**
     * 根据route name生成url
     *
     * @param string $name            
     * @param array $parameters            
     * @param boolean $absolute            
     * @return string
     */
    function generateByName($name, $parameters = [], $absolute = true);

    /**
     * 根据action生成url
     *
     * @param string $action            
     * @param array $parameters            
     * @param boolean $absolute            
     * @return string
     */
    function generateByAction($action, $parameters = [], $absolute = true);

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