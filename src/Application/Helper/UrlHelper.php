<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Helper;

use Slince\Routing\Router;
use Slince\Application\Kernel;

class UrlHelper extends Helper
{
    /**
     * router
     * 
     * @var Router
     */
    protected $router;
    
    function __construct()
    {
        parent::__construct();
        $this->router = Kernel::instance()->getRouter();
    }
    function build($name, $parameters = [], $absolute = false)
    {
        return $this->router->generateByName($name, $parameters, $absolute);
    }
    
    function to($action, $parameters = [], $absolute = false)
    {
        return $this->router->generateByAction($action, $parameters, $absolute);
    }
}