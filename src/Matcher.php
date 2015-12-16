<?php
/**
 * slince router library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

class Matcher implements MatcherInterface
{

    /**
     * request context
     * 
     * @var RequestContext
     */
    protected $_context;

    /**
     * validator collection
     *
     * @var ValidatorCollection
     */
    protected $_validators;
    
    /**
     * route验证结果
     * 
     * @var array
     */
    protected $_report = [];

    function __construct(RequestContext $context = null)
    {
        $this->_context = $context;
        $this->_validators = ValidatorCollection::create();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Routing\MatcherInterface::match()
     */
    function match($path, RouteCollection $routes)
    {
        $path = '/' . ltrim($path, '/');
        $this->_context->setParameter('path', $path);
        //如果匹配子集前缀则进入子集匹配
        $prefix = strtok($path , '/'); 
        if ($routes->hasCollection($prefix)) {
            return $this->match(substr($path, strlen($prefix) + 1), $routes->getCollection($prefix));
        }
        //查找符合条件的route
        foreach ($routes as $route) 
        {
            if ($this->_validate($route)) {
                $route->setRouteParameters($this->_handleRouteParameters($route));
                return $route;
            }
        }
        //未找到route时，检测是否有被MethodValidator拦截的route，
        //有则抛MethodNotAllowedException,无抛RouteNotFoundException
        if (! empty($this->_report[MethodValidator::$id])) {
            $methods = array_map(function(RouteInterface $route) {
                return $route->getMethods();
            }, $this->_report[MethodValidator::$id]);
            $methods = array_unique(call_user_func_array('array_merge', $methods));
            throw new MethodNotAllowedException($methods);
        }
        throw new RouteNotFoundException();
    }

    /**
     * 添加一个自定义validator
     * 
     * @param ValidatorInterface $validator
     */
    function add($validator)
    {
        if ($validator instanceof \Closure) {
            $validator = ValidatorFactory::createCallbackValidator($validator);
        }
        $this->_validators->add($validator);
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\MatcherInterface::getValidators()
     */
    function getValidators()
    {
        return $this->_validators;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\MatcherInterface::setValidators()
     */
    function setValidators(ValidatorCollection $validatorCollection)
    {
        $this->_validators = $validatorCollection;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\MatcherInterface::setContext()
     */
    function setContext(RequestContext $context)
    {
        $this->_context = $context;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\MatcherInterface::getContext()
     */
    function getContext()
    {
        return $this->_context;
    }
    
    /**
     * 验证route是否通过
     *
     * @param RouteInterface $route
     */
    protected function _validate($route)
    {
        foreach ($this->_validators as $validator) {
            //如果有规则没有被验证通过，则直接终止接下来的验证
            //并且记录拦截情况
            if (! $validator->validate($route, $this->_context)) {
                $this->_writeReport($validator::$id, $route);
                return false;
            }
        }
        return true;
    }
    /**
     * 
     * @param unknown $validatorId
     * @param RouteInterface $route
     */
    protected function _writeReport($validatorId, RouteInterface $route)
    {
        if (! isset($this->_report[$validatorId])) {
            $this->_report[$validatorId] = [];
        }
        $this->_report[$validatorId][] =$route;
    }
    
    /**
     * 处理路由参数
     * 
     * @param RouteInterface $route
     * @return array
     */
    protected function _handleRouteParameters(RouteInterface $route)
    {
        $catchedParameters = call_user_func_array('array_merge', $route->getReport());
        $variables = $route->getCompiledRoute()->getVariables();
        return array_intersect_key($catchedParameters, array_flip($variables));
    }
}