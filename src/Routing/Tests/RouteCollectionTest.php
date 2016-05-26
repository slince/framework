<?php
namespace Slince\Routing\Tests;

use Slince\Routing\Route;
use Slince\Routing\RouteCollection;

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    function testAddRoute()
    {
        $routes = new RouteCollection();
        $this->assertEquals([], $routes->all());
        $routes->add(new Route('/path', ''));
        $this->assertNotEmpty($routes->all());
    }

    function testConstruct()
    {
        $routes = new RouteCollection([
            new Route('/foo', ''),
            new Route('/bar', ''),
        ]);
        $this->assertCount(2, $routes);
    }

    function testNamedRoute()
    {
        $routes = new RouteCollection();
        $route = new Route('/foo', '');
        $routes->add($route, 'foo');
        $this->assertEquals($route, $routes->getByName('foo'));
    }

    function testActionRoute()
    {
        $routes = new RouteCollection();
        $route = new Route('/foo', 'foo@bar');
        $routes->add($route, 'foo');
        $this->assertEquals($route, $routes->getByAction('foo@bar'));
    }

    function testRouteBuilder()
    {
        $routes = new RouteCollection();
        $route = $routes->http('/foo1', [
            'name' => 'foo1'
        ]);
        $this->assertEquals([], $route->getMethods());
        $route = $routes->get('/foo2', [
            'name' => 'foo2'
        ]);
        $this->assertEquals(['get'], $route->getMethods());
    }
}