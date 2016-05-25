<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

class Route implements RouteInterface
{

    /**
     * path
     * @var string
     */
    protected $path;

    /**
     * action
     * @var mixed
     */
    protected $action;

    /**
     * 默认参数
     * @var array
     */
    protected $defaults;

    /**
     * requirements
     * @var array
     */
    protected $requirements;

    /**
     * schemes
     * @var array
     */
    protected $schemes;

    /**
     * host
     * @var string
     */
    protected $host;

    /**
     * methods
     * @var array
     */
    protected $methods;

    /**
     * parameters
     * @var array
     */
    protected $parameters;

    /**
     * 是否已经编译
     * @var bool
     */
    protected $isCompiled = false;

    /**
     * host regex
     *
     * @var string
     */
    protected $hostRegex;

    /**
     * path regex
     *
     * @var string
     */
    protected $pathRegex;

    /**
     * 变量
     * @var array
     */
    protected $variables = [];

    function __construct(
        $path,
        $action,
        array $defaults = [],
        array $requirements = [],
        $host = '',
        array $schemes = [],
        array $methods = []
    ) {
        $this->setPath($path);
        $this->setAction($action);
        $this->setDefaults($defaults);
        $this->setRequirements($requirements);
        $this->setHost($host);
        $this->setSchemes($schemes);
        $this->setMethods($methods);
    }

    /**
     * 设置path
     * @param string $path
     * @return $this
     */
    function setPath($path)
    {
        $this->path = '/' . trim($path, '/');
        return $this;
    }

    /**
     * 获取path
     * @return string
     */
    function getPath()
    {
        return $this->path;
    }

    /**
     * 获取path regex
     * @return string
     */
    function getPathRegex()
    {
        return $this->pathRegex;
    }

    /**
     * 设置action
     * @param $action
     * @return $this
     */
    function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * 获取action
     * @return mixed
     */
    function getAction()
    {
        return $this->action;
    }

    /**
     * 获取默认
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * 设置默认参数
     * @param array $defaults
     * @return $this
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
        return $this;
    }

    /**
     * 获取默认参数
     * @param $name
     * @return mixed|null
     */
    public function getDefault($name)
    {
        return isset($this->defaults[$name]) ? $this->defaults[$name] : null;
    }

    /**
     * 检查是否有默认参数
     * @param $name
     * @return bool
     */
    public function hasDefault($name)
    {
        return isset($this->defaults[$name]);
    }

    /**
     * 设置parameter
     * @param string $name
     * @param mixed $parameter
     * @return $this
     */
    function setParameter($name, $parameter)
    {
        $this->parameters[$name] = $parameter;
        return $this;
    }


    /**
     * 获取parameter
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    function getParameter($name, $default = null)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    /**
     * 是否存在参数
     * @param string $name
     * @return bool
     */
    function hasParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * 设置parameters
     * @param array $parameters
     * @return $this\
     */
    function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * 获取parameters
     * @return array
     */
    function getParameters()
    {
        return $this->parameters;
    }

    /**
     * 设置requirements
     * @param array $requirements
     * @return $this
     */
    function setRequirements(array $requirements)
    {
        $this->requirements = $requirements;
        return $this;
    }

    /**
     * 设置单个requirement
     * @param string $name
     * @param string $requirement
     * @return $this
     */
    function setRequirement($name, $requirement)
    {
        $this->requirements[$name] = $requirement;
        return $this;
    }

    /**
     * 添加requirements
     * @param array $requirements
     * @return RouteInterface
     */
    function addRequirements(array $requirements)
    {
        $this->requirements += $requirements;
        return $this;
    }

    /**
     * 获取requirements
     * @return array
     */
    function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * 获取requirement
     * @param string $name
     * @param null $default
     * @return string|null
     */
    function getRequirement($name, $default = null)
    {
        return isset($this->requirements[$name]) ? $this->requirements[$name] : $default;
    }

    /**
     * 设置schemes
     * @param array $schemes
     * @return $this
     */
    function setSchemes(array $schemes)
    {
        $this->schemes = $schemes;
        return $this;
    }

    /**
     * 获取schemes
     * @return array
     */
    function getSchemes()
    {
        return $this->schemes;
    }

    /**
     * 设置methods
     * @param array $methods
     * @return $this
     */
    function setMethods(array $methods)
    {
        $this->methods = array_map('strtolower', $methods);
        return $this;
    }

    /**
     * 获取method
     * @return array
     */
    function getMethods()
    {
        return $this->methods;
    }

    /**
     * 设置host
     * @param string $host
     * @return $this
     */
    function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * 获取host
     * @return string
     */
    function getHost()
    {
        return $this->host;
    }

    /**
     * 获取host regex
     * @return string
     */
    function getHostRegex()
    {
        return $this->hostRegex;
    }

    /**
     * route是否已经编译
     * @return boolean
     */
    public function isCompiled()
    {
        return $this->isCompiled;
    }
    
    /**
     * 编译route
     * @param boolean $recompile
     * @return Route
     */
    function compile($recompile = false)
    {
        if (!$this->isCompiled || $recompile) {
            $this->hostRegex = $this->parseToRegex($this->getHost());
            $this->pathRegex = $this->parseToRegex($this->getPath());
            $this->isCompiled = true;
        }
        return $this;
    }

    /**
     * 获取variables
     * @return array
     */
    function getVariables()
    {
        return $this->variables;
    }

    /**
     * 解析成标准的正则字符串
     * @param string $path
     * @return string
     */
    protected function parseToRegex($path)
    {
        $regex = preg_replace_callback('#\{([a-zA-Z0-9_,]*)\}#i', function ($matches) {
            $this->variables[] = $matches[1];
            return "(?P<{$matches[1]}>" . (isset($this->requirements[$matches[1]]) ? $this->requirements[$matches[1]] : '.+') . ')';
        }, $path);
        return "#^{$regex}$#i";
    }
}