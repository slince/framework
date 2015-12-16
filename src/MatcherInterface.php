<?php
/**
 * slince router library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Router;

use Slince\Router\Validator\ValidatorInterface;

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
     * 设置 validator collection
     *
     * @param ValidatorCollection $validatorCollection            
     */
    function setValidators(ValidatorCollection $validatorCollection);

    /**
     * 获取validator collection
     *
     * @return ValidatorCollection
     */
    function getValidators();

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