<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

class RouteCollection implements \Countable, \IteratorAggregate
{

    use RouteBuilderTrait;
    /**
     * 路由集合的前缀
     *
     * @var string
     */
    protected $_prefix = '';

    /**
     * route集合
     *
     * @var array
     */
    protected $_routes = [];
    
    /**
     * name集合
     *
     * @var array
     */
    protected $_nameRoutes = [];
    
    /**
     * name集合
     *
     * @var array
     */
    protected $_actionRoutes = [];

    function __construct(array $routes = [])
    {
        $this->_routes = $routes;
    }

    /**
     * 添加路由
     *
     * @param RouteInterface $route            
     */
    function add(RouteInterface $route)
    {
        
        if ($name = $route->getParameter('name') !== null) {
            $this->_nameRoutes[$name] = $route;
        }
        $action = $route->getAction();
        if (is_scalar($action)) {
            $this->_actionRoutes[$action] = $route;
        }
        $this->_routes[] = $route;
    }

    /**
     * 清除集合
     *
     * @param array $routes            
     */
    function clear()
    {
        $this->_routes = [];
    }
    
    /**
     * 根据name获取route
     *
     * @param  string  $name
     * @return Route|null
     */
    public function getByName($name)
    {
        return isset($this->_nameRoutes[$name]) ? $this->_nameRoutes[$name] : null;
    }

    /**
     * 根据action获取route
     *
     * @param  string  $action
     * @return \Illuminate\Routing\Route|null
     */
    public function getByAction($action)
    {
        return isset($this->_actionRoutes[$action]) ? $this->_actionRoutes[$action] : null;
    }

    /**
     * 获取集合下所有的route
     *
     * @return array
     */
    function all()
    {
        return $this->_routes;
    }

    function setPrefix($prefix)
    {
        if (! empty($prefix)) {
            $this->_prefix = '/' . trim($prefix, '/');
        }
    }

    function getPreifx()
    {
        return $this->_prefix;
    }

    /**
     * (non-PHPdoc)
     *
     * @see Countable::count()
     */
    function count()
    {
        return count($this->_routes);
    }

    /**
     * (non-PHPdoc)
     *
     * @see IteratorAggregate::getIterator()
     */
    function getIterator()
    {
        return new \ArrayIterator($this->_routes);
    }
    
    function getRoutes()
    {
        return $this;
    }
}