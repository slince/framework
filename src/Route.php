<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

use Slince\Routing\Exception\InvalidParameterException;

class Route implements RouteInterface
{

    /**
     * path
     *
     * @var string
     */
    protected $_path;

    /**
     * action
     *
     * @var mixed
     */
    protected $_action;
    
    /**
     * 默认参数
     *
     * @var array
     */
    protected $_defaults;

    /**
     * requirements
     *
     * @var array
     */
    protected $_requirements;

    /**
     * schemes
     *
     * @var array
     */
    protected $_schemes;
    
    /**
     * host
     *
     * @var string
     */
    protected $_host;

    /**
     * methods
     *
     * @var array
     */
    protected $_methods;
    
    /**
     * parameters
     *
     * @var array
     */
    protected $_parameters;

    protected $_isCompiled = false;
    /**
     * host regex
     *
     * @var string
     */
    protected $_hostRegex;
    
    /**
     * path regex
     * 
     * @var string
     */
    protected $_pathRegex;
    
    protected $_variables = [];
    
    /**
     * 验证之后的路由参数
     *
     * @var array
     */
    protected $_routeParameters;

    function __construct($path, $action, array $defaults = [], array $requirements = [], $host = '', array $schemes = [], array $methods = [])
    {
        $this->setPath($path);
        $this->setAction($action);
        $this->setDefaults($defaults);
        $this->setRequirements($requirements);
        $this->setHost($host);
        $this->setSchemes($schemes);
        $this->setMethods($methods);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setPath()
     */
    function setPath($path)
    {
        $this->_path = '/' . trim($path, '/');
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getPath()
     */
    function getPath()
    {
        return $this->_path;
    }
    
    function getPathRegex()
    {
        return $this->_pathRegex;
    }
    
    function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }

    function getAction()
    {
        return $this->_action;
    }

    /**
     * Returns the defaults.
     *
     * @return array The defaults
     */
    public function getDefaults()
    {
        return $this->_defaults;
    }

    /**
     * Sets the defaults.
     *
     * This method implements a fluent interface.
     *
     * @param array $defaults
     *            The defaults
     *            
     * @return Route The current Route instance
     */
    public function setDefaults(array $defaults)
    {
        $this->_defaults = $defaults;
        return $this;
    }

    /**
     * Gets a default value.
     *
     * @param string $name
     *            A variable name
     *            
     * @return mixed The default value or null when not given
     */
    public function getDefault($name)
    {
        return isset($this->_defaults[$name]) ? $this->_defaults[$name] : null;
    }

    /**
     * Checks if a default value is set for the given variable.
     *
     * @param string $name
     *            A variable name
     *            
     * @return bool true if the default value is set, false otherwise
     */
    public function hasDefault($name)
    {
        return isset($this->_defaults[$name]);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setParameter()
     */
    function setParameter($name, $parameter)
    {
        $this->_parameters[$name] = $parameter;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getParameter()
     */
    function getParameter($name, $default = null)
    {
        return isset($this->_parameters[$name]) ? $this->_parameters[$name] : $default;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::hasParameter()
     */
    function hasParameter($name)
    {
        return isset($this->_parameters[$name]);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setParameters()
     */
    function setParameters(array $parameters)
    {
        $this->_parameters = $parameters;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getParameters()
     */
    function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setRequirements()
     */
    function setRequirements(array $requirements)
    {
        $this->_requirements = $requirements;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setRequirement()
     */
    function setRequirement($name, $requirement)
    {
        $this->_requirements[$name] = $requirement;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getRequirements()
     */
    function getRequirements()
    {
        return $this->_requirements;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::addRequirements()
     */
    function addRequirements(array $requirements)
    {
        $this->_requirements += $requirements;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getRequirement()
     */
    function getRequirement($name, $default = null)
    {
        return isset($this->_requirements[$name]) ? $this->_requirements[$name] : $default;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setSchemes()
     */
    function setSchemes(array $schemes)
    {
        $this->_schemes = $schemes;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getSchemes()
     */
    function getSchemes()
    {
        return $this->_schemes;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setHost()
     */
    function setHost($host)
    {
        $this->_host = $host;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getHost()
     */
    function getHost()
    {
        return $this->_host;
    }
    
    function getHostRegex()
    {
        return $this->_hostRegex;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::setMethods()
     */
    function setMethods(array $methods)
    {
        $this->_methods = array_map('strtolower', $methods);
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Router\RouteInterface::getMethods()
     */
    function getMethods()
    {
        return $this->_methods;
    }

    /**
     * 编译route
     * 
     * @param string $recompile
     * @return Route
     */
    function compile($recompile = false)
    {
        if (! $this->_isCompiled || $recompile) {
            $this->_hostRegex = $this->_parseToRegex($this->getHost());
            $this->_pathRegex = $this->_parseToRegex($this->getPath());
            $this->_isCompiled = true;
        }
        return $this;
    }
    
    function getVariables()
    {
        return $this->_variables;
    }
    
    /**
     * 解析成标准的正则字符串
     *
     * @param string $path
     * @return string
     */
    protected function _parseToRegex($path)
    {
        $regex = preg_replace_callback('#\{([a-zA-Z0-9_,]*)\}#i', function($matches) {
            $this->_variables[] = $matches[1];
            return "(?P<{$matches[1]}>" . (isset($this->_requirements[$matches[1]]) ? $this->_requirements[$matches[1]] : '.+') . ')';
        }, $path);
        return "#^{$regex}$#i";
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\RouteInterface::setRouteParameters()
     */
    function setRouteParameters(array $parameters)
    {
        $this->_routeParameters = $parameters;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Routing\RouteInterface::getRouteParameters()
     */
    function getRouteParameters()
    {
        return $this->_routeParameters;
    }
}