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
     * 最近一个route的variables
     *
     * @var array
     */
    protected $_routeVariables = [];
    
    function __construct(RequestContext $context)
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
        $parameters = $this->getParameters($route, $parameters);
        $uri = '';
        // 生成绝对路径，需要构建scheme host port
        if ($absolute) {
            list ($scheme, $port) = $this->_getRouteSchemeAndPort($route);
            $host = $this->_getRouteHost($route, $parameters);
            $uri .= "{$scheme}://{$host}{$port}";
        }
        $uri .= $this->_getRoutePath($route, $parameters);
        // 提供的多出的数据作为query string
        $extraParameters = array_diff_key($parameters, array_flip($this->_routeVariables));
        if ($extraParameters && $query = http_build_query($extraParameters, '', '&')) {
            $uri .= '?' . $query;
        }
        return $uri;
    }
    
    function getParameters(RouteInterface $route, $parameters)
    {
        return array_replace(
            $route->getDefaults(),
            $this->_context->getParameters(), 
            $parameters
        );
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
     * 获取route的host
     * @param RouteInterface $route
     * @return string
     */
    protected function _getRouteHost(RouteInterface $route, $parameters)
    {
        // 如果route没有主机域名限制则直接使用环境中主机
        $requireHost = $route->getHost();
        if (empty($requireHost)) {
            return $this->_context->getHost();
        }
        // 有限制则根据route的host限制生成域名
        return $this->_formateRouteHostOrPath($requireHost, $parameters, $route->getRequirements());
    }

    /**
     * 获取route的pathinfo部分
     * @param RouteInterface $route
     * @return string
     */
    protected function _getRoutePath(RouteInterface $route, $parameters)
    {
        return $this->_formateRouteHostOrPath($route->getPath(), $parameters, $route->getRequirements());
    }

    /**
     * 格式化route的host和pathinfo部分
     * @param string $path
     * @param array $parameters
     * @param array $requirements
     * @throws InvalidParameterException
     * @return string
     */
    protected function _formateRouteHostOrPath($path, $parameters, $requirements = [])
    {
        return preg_replace_callback('#\{([a-zA-Z0-9_,]*)\}#', function ($matches) use($parameters, $requirements)
        {
            //为了避免重新编译route得到variable此处代为获取route variable
            $this->_routeVariables[] = $matches[1];
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