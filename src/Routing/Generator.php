<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

use Slince\Routing\Exception\InvalidArgumentException;

class Generator implements GeneratorInterface
{

    /**
     * request context
     * @var RequestContext
     */
    protected $context;

    /**
     * 严格检查
     * @var boolean
     */
    protected $strictRequirements = false;

    /**
     * 最近一个route的variables
     * @var array
     */
    protected $routeVariables = [];

    function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * 设置上下文
     * @param RequestContext $context
     */
    function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * 获取上下文
     * @return RequestContext $context
     */
    function getContext()
    {
        return $this->context;
    }

    /**
     * 严格匹配模式
     * @param boolean $enabled
     */
    public function setStrictRequirements($enabled)
    {
        $this->strictRequirements = $enabled;
    }

    /**
     * 是否严格匹配模式
     * @return boolean
     */
    public function isStrictRequirements()
    {
        return $this->strictRequirements;
    }

    /**
     * 生成特定路由的url
     * @param RouteInterface $route
     * @param array $parameters
     * @param boolean $absolute
     * @return string
     */
    function generate(RouteInterface $route, $parameters = [], $absolute = true)
    {
        $parameters = $this->getParameters($route, $parameters);
        $uri = '';
        // 生成绝对路径，需要构建scheme host port
        if ($absolute) {
            list ($scheme, $port) = $this->getRouteSchemeAndPort($route);
            $host = $this->getRouteHost($route, $parameters);
            $uri .= "{$scheme}://{$host}{$port}";
        }
        $uri .= $this->getRoutePath($route, $parameters);
        // 提供的多出的数据作为query string
        $extraParameters = array_diff_key($parameters, array_flip($this->routeVariables));
        if ($extraParameters && $query = http_build_query($extraParameters, '', '&')) {
            $uri .= '?' . $query;
        }
        return $uri;
    }

    /**
     * 获取路由的参数，三部分
     * @param RouteInterface $route
     * @param $parameters
     * @return array
     */
    function getParameters(RouteInterface $route, $parameters)
    {
        return array_replace(
            $route->getDefaults(),
            $this->context->getParameters(),
            $parameters
        );
    }

    /**
     * 获取route的scheme和port
     * @param RouteInterface $route
     * @return array
     */
    protected function getRouteSchemeAndPort(RouteInterface $route)
    {
        $scheme = $this->context->getScheme();
        $requiredSchemes = $route->getSchemes();
        //如果当前请求协议不在route要求的协议内则使用第一个要求的协议
        if (!empty($requiredSchemes) && !in_array($scheme, $requiredSchemes)) {
            $scheme = reset($requiredSchemes);
        }
        $port = '';
        if (strcasecmp($scheme, 'http') == 0 && $this->context->getHttpPort() != 80) {
            $port = ':' . $this->context->getHttpPort();
        } elseif (strcasecmp($scheme, 'https') == 0 && $this->context->getHttpsPort() != 443) {
            $port = ':' . $this->context->getHttpsPort();
        }
        return [$scheme, $port];
    }

    /**
     * 获取route的host
     * @param RouteInterface $route
     * @param array $parameters
     * @return string
     */
    protected function getRouteHost(RouteInterface $route, $parameters)
    {
        // 如果route没有主机域名限制则直接使用环境中主机
        $requireHost = $route->getHost();
        if (empty($requireHost)) {
            return $this->context->getHost();
        }
        // 有限制则根据route的host限制生成域名
        return $this->formatRouteHostOrPath($requireHost, $parameters, $route->getRequirements());
    }

    /**
     * 获取route的pathinfo部分
     * @param RouteInterface $route
     * @param array $parameters
     * @return string
     */
    protected function getRoutePath(RouteInterface $route, $parameters)
    {
        return $this->formatRouteHostOrPath($route->getPath(), $parameters, $route->getRequirements());
    }

    /**
     * 格式化route的host和pathinfo部分
     * @param string $path
     * @param array $parameters
     * @param array $requirements
     * @throws InvalidArgumentException
     * @return string
     */
    protected function formatRouteHostOrPath($path, $parameters, $requirements = [])
    {
        return preg_replace_callback('#\{([a-zA-Z0-9_,]*)\}#', function ($matches) use ($parameters, $requirements) {
            //为了避免重新编译route得到variable此处代为获取route variable
            $this->routeVariables[] = $matches[1];
            //严格模式必须提供参数
            if (!isset($parameters[$matches[1]]) && $this->strictRequirements) {
                throw new InvalidArgumentException(sprintf('Missing parameter "%s"', $matches[1]));
            }
            $supportVariable = isset($parameters[$matches[1]]) ? $parameters[$matches[1]] : '';
            if ($this->strictRequirements) {
                //如果不匹配要求的正则则抛出异常
                if (isset($requirements[$matches[1]]) && !preg_match('#^' . $requirements[$matches[1]] . '$#',
                        $supportVariable)
                ) {
                    $message = sprintf('Parameter "%s" must match "%s" ("%s" given) to generate a corresponding URL.',
                        $matches[1], $requirements[$matches[1]], $supportVariable);
                    throw new InvalidArgumentException($message);
                }
            }
            return $supportVariable;
        }, $path);
    }
}