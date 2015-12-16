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
    protected $_namedRoutes = [];
    
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
     * 实例化当前对象
     *
     * @param arrau $routes            
     * @return \Slince\Routing\RouteCollection
     */
    static function create($routes = [])
    {
        return new static($routes);
    }

    /**
     * 添加路由
     *
     * @param RouteInterface $route            
     */
    function add(RouteInterface $route)
    {
        $this->_routes[] = $route;
    }

    /**
     * 更换路由集合
     *
     * @param array $routes            
     */
    function clear()
    {
        $this->_routes = [];
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

    function setPreifx($prefix)
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
    
    function getCollection()
    {
        return $this;
    }
}