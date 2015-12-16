<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

use Slince\Routing\Exception\InvalidParameterException;
use Slince\Routing\Exception\RouteNotFoundException;

class Generator implements GeneratorInterface
{

    /**
     * request context
     *
     * @var RequestContext
     */
    protected $_context;
    
    /**
     * 严格检查
     * 
     * @var boolean
     */
    protected $_strictRequirements = false;

    /**
     * 存储最近一个route的route param
     *
     * @var array
     */
    protected $_routeParameters;
    
    function __construct(RequestContext $context = null)
    {
        $this->_context = $context;
    }
    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Routing\GeneratorInterface::setContext()
     */
    function setContext(RequestContext $context)
    {
        $this->_context = $context;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Routing\GeneratorInterface::getContext()
     */
    function getContext()
    {
        return $this->_context;
    }

    /**
     * 严格匹配模式
     * @param boolean $enabled
     */
    public function setStrictRequirements($enabled)
    {
        $this->_strictRequirements = $enabled;
    }
    
    /**
     * 是否严格匹配模式
     * 
     * @return boolean
     */
    public function isStrictRequirements()
    {
        return $this->_strictRequirements;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\GeneratorInterface::generate()
     */
    function generate(RouteInterface $route, $parameters = [], $absolute = true)
    {
        $compiledRoute = $route->getCompiledRoute();
        // 提供的初始化route parameter
        $this->_routeParameters = array_replace($route->getDefaults(), 
            $this->_context->getParameters(), 
            $parameters
        );
        $uri = '';
        // 生成绝对路径，需要构建scheme domain port
        if ($absolute) {
            list ($scheme, $port) = $this->_getRouteSchemeAndPort($route);
            $domain = $this->_getRouteDomain($route);
            $uri .= "{$scheme}://{$domain}{$port}";
        }
        $uri .= $this->_getRoutePath($route);
        // 提供的多出的数据作为query string
        $extra = array_diff_key($parameters, $route->getRequirements());
        if ($extra && $query = http_build_query($extra, '', '&')) {
            $uri .= '?' . $query;
        }
        return $uri;
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\GeneratorInterface::generateByName()
     */
    function generateByName($name, $parameters = [], $absolute = true)
    {
        $route = RouteStore::newInstance()->getByName($name);
        if (is_null($route)) {
            throw new RouteNotFoundException(sprintf('Route "%s" not defined.', $name));
        }
        return $this->generate($route, $parameters, $absolute);
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\GeneratorInterface::generateByAction()
     */
    function generateByAction($action, $parameters = [], $absolute = true)
    {
        $route = RouteStore::newInstance()->getByAction($action);
        if (is_null($route)) {
            throw new RouteNotFoundException(sprintf('Action "%s" not defined.', $action));
        }
        return $this->generate($route, $parameters, $absolute);
    }

    /**
     * 获取route的scheme和port
     * 
     * @param RouteInterface $route
     * @return array
     */
    protected function _getRouteSchemeAndPort(RouteInterface $route)
    {
        $scheme = $this->_context->getScheme();
        $requiredSchemes = $route->getSchemes();
        //如果当前请求协议不在route要求的协议内则使用第一个要求的协议
        if (! empty($requiredSchemes) && ! in_array($scheme, $requiredSchemes)) {
            $scheme = reset($requiredSchemes);
        }
        $port = '';
        if (strcasecmp($scheme, 'http') == 0 && $this->_context->getHttpPort() != 80) {
            $port = ':' . $this->_context->getHttpPort();
        } elseif (strcasecmp($scheme, 'https') == 0 && $this->_context->getHttpsPort() != 443) {
            $port = ':' . $this->_context->getHttpsPort();
        }
        return [$scheme, $port];
    }

    /**
     * 获取route的domain
     * @param RouteInterface $route
     * @return string
     */
    protected function _getRouteDomain(RouteInterface $route)
    {
        // 如果route没有主机域名限制则直接使用环境中主机
        $requireDomain = $route->getDomain();
        if (empty($requireDomain)) {
            return $this->_context->getHost();
        }
        // 有限制则根据route的host限制生成域名
        return $this->_formateRouteParameters($requireDomain, $this->_routeParameters, $route->getRequirements());
    }

    /**
     * 获取route的pathinfo部分
     * @param RouteInterface $route
     * @return string
     */
    protected function _getRoutePath(RouteInterface $route)
    {
        return $this->_formateRouteParameters($route->getFullPath(), $this->_routeParameters, $route->getRequirements());
    }

    /**
     * 格式化route的host和pathinfo部分
     * @param string $path
     * @param array $parameters
     * @param array $requirements
     * @throws InvalidParameterException
     * @return string
     */
    protected function _formateRouteParameters($path, $parameters, $requirements = [])
    {
        return preg_replace_callback('#\{([a-zA-Z0-9_,]*)\}#', function ($matches) use($parameters, $requirements)
        {
            $supportVariable = isset($parameters[$matches[1]]) ? $parameters[$matches[1]] : '';
            //非严格匹配类型直接返回提供的类型
            if (! $this->_strictRequirements) {
                return $supportVariable;
            }
            //如果不匹配要求的正则则抛出异常
            if (isset($requirements[$matches[1]]) && ! preg_match('#^' . $requirements[$matches[1]] . '$#', $supportVariable)) {
                $message = sprintf('Parameter "%s" must match "%s" ("%s" given) to generate a corresponding URL.', $matches[1], $requirements[$matches[1]], $supportVariable);
                throw new InvalidParameterException($message);
            }
            return $supportVariable;
        }, $path);
    }
}