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
    protected $_names = [];

    /**
     * action集合
     *
     * @var array
     */
    protected $_actions = [];

    function __construct(array $routes = [])
    {
        $this->_routes = $routes;
    }

    /**
     * 添加路由
     *
     * @param RouteInterface $route
     */
    function add(RouteInterface $route, $name = null)
    {
        if (!is_null($name)) {
            $this->_names[$name] = $route;
        }
        $action = $route->getAction();
        if (is_scalar($action)) {
            $this->_actions[$action] = $route;
        }
        $this->_routes[] = $route;
    }

    /**
     * 根据name获取route
     *
     * @param string $name
     * @return Route|null
     */
    public function getByName($name)
    {
        return isset($this->_names[$name]) ? $this->_names[$name] : null;
    }

    /**
     * 根据action获取route
     *
     * @param string $action
     * @return Route|null
     */
    public function getByAction($action)
    {
        return isset($this->_actions[$action]) ? $this->_actions[$action] : null;
    }

    function getNameRoute()
    {
        return $this->_namedRoutes;
    }

    function getActionRoutes()
    {
        return $this->_actions;
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