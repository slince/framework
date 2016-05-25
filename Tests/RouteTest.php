<?php
namespace Slince\Routing\Tests;

use Slince\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    protected function createRoute(
        $path,
        $action = '',
        array $defaults = [],
        array $requirements = [],
        $host = '',
        array $schemes = [],
        array $methods = []
    ) {
        return new Route($path,
            $action,
            $defaults,
            $requirements,
            $host,
            $schemes,
            $methods
        );
    }

    function testInterface()
    {
        $route = new Route('/path', '');
        $this->assertInstanceOf('Slince\Routing\RouteInterface', $route);
    }
    
    function testPath()
    {
        $route = new Route('/path', '');
        $this->assertEquals('/path', $route->getPath());
        $route->setPath('/path/{bar}');
        $this->assertEquals('/path/{bar}', $route->getPath());
        $route->setPath('');
        $this->assertEquals('/', $route->getPath());
        $route->setPath('//path');
        $this->assertEquals('/path', $route->getPath());
        $route->setPath('path/');
        $this->assertEquals('/path', $route->getPath());
        $this->assertEquals($route, $route->setPath('/'));
    }

    function testDefaults()
    {
        $route = new Route('/path', '');
        $route->setDefaults(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $route->getDefaults());
        $this->assertEquals('bar', $route->getDefault('foo'));
        $this->assertNull($route->getDefault('undefined'));
        $this->assertTrue($route->hasDefault('foo'));
    }

    function testRequirements()
    {
        $route = new Route('/path', '');
        $route->setRequirements(['id' => '\d+']);
        $this->assertEquals(['id' => '\d+'], $route->getRequirements());
        $this->assertEquals('\d+', $route->getRequirement('id'));
        $this->assertNull($route->getRequirement('undefined'));
        $route->addRequirements(['name'=>'\w+']);
        $this->assertEquals(['id' => '\d+', 'name'=>'\w+'], $route->getRequirements());
    }

    function testSchemes()
    {
        $route = new Route('/path', '');
        $this->assertEquals([], $route->getSchemes());
        $route->setSchemes(['http']);
        $this->assertEquals(['http'], $route->getSchemes());
    }

    function testMethods()
    {
        $route = new Route('/path', '');
        $this->assertEquals([], $route->getMethods());
        $route->setMethods(['get']);
        $this->assertEquals(['get'], $route->getMethods());
    }

    function testHost()
    {
        $route = new Route('/path', '');
        $this->assertEquals('', $route->getHost());
        $route->setHost('www.domain.com');
        $this->assertEquals('www.domain.com', $route->getHost());
    }

    function testCompile()
    {
        $route = new Route('/users/{id}/{action}', '');
        $this->assertFalse($route->isCompiled());
        $route->compile();
        $this->assertTrue($route->isCompiled());
        $this->assertEquals('#^/users/(?P<id>.+)/(?P<action>.+)$#i', $route->getPathRegex());
        $this->assertEquals(['id', 'action'], $route->getVariables());
        $route->setHost('{mainDomain}.domain.com');
        $route->compile(true);
        $this->assertTrue($route->isCompiled());
        $this->assertEquals('#^/users/(?P<id>.+)/(?P<action>.+)$#i', $route->getPathRegex());
        $this->assertEquals('#^(?P<mainDomain>.+).domain.com$#i', $route->getHostRegex());
    }
}