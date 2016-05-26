<?php
namespace Slince\Routing\Tests;

use Slince\Routing\Matcher;
use Slince\Routing\RequestContext;
use Slince\Routing\Route;
use Slince\Routing\RouteCollection;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    function testContext()
    {
        $matcher = new Matcher();
        $this->assertNull($matcher->getContext());
        $matcher->setContext(RequestContext::create());
        $this->assertInstanceOf('Slince\Routing\RequestContext', $matcher->getContext());
    }
    
    function testSimpleMatcher()
    {
        $routes = new RouteCollection();
        $route = new Route('/path', '');
        $routes->add($route);
        $matcher = new Matcher();
        $_route = $matcher->match('/path', $routes);
        $this->assertEquals($_route, $route);
    }

    function testMatchWithRegex()
    {
        $routes = new RouteCollection();
        $route = new Route('/foo/{id}/bar/{name}', '');
        $route->setHost('{main}.foo.com');
        $routes->add($route);

        $matcher = new Matcher();
        $context = RequestContext::create();
        $context->setHost('www.foo.com');
        $matcher->setContext($context);

        $_route = $matcher->match('/foo/100/bar/steven', $routes);
        $this->assertEquals($_route, $route);
        $this->assertEquals([
            'main' => 'www',
            'id' => 100,
            'name' => 'steven'
        ], $route->getParameters());
    }

    function testRouteNotFoundException()
    {
        $routes = new RouteCollection();
        $route = new Route('/foo/{id}/bar/{name}', '');
        $route->setHost('{main}.foo.com');
        $route->setRequirements([
            'id' => '\d+',
            'name' => '\w+',
            'main' => 'm'
        ]);
        $routes->add($route);

        $matcher = new Matcher();
        $context = RequestContext::create();
        $context->setHost('www.foo.com');
        $matcher->setContext($context);
        $this->setExpectedExceptionRegExp('Slince\Routing\Exception\RouteNotFoundException');
        $matcher->match('/foo/100/bar/steven', $routes);
    }

    function testMethodNotAllowedException()
    {
        $routes = new RouteCollection();
        $route = new Route('/foo/{id}/bar/{name}', '');
        $route->setMethods(['POST', 'PUT']);
        $routes->add($route);
        $context = RequestContext::create();
        $this->assertEquals(['post', 'put'], $route->getMethods());
        $this->assertEquals('GET', $context->getMethod());
        $matcher = new Matcher($context);
        $this->setExpectedExceptionRegExp('Slince\Routing\Exception\MethodNotAllowedException');
        $matcher->match('/foo/100/bar/steven', $routes);
    }

    function testSchemes()
    {
        $routes = new RouteCollection();
        $route = new Route('/foo/{id}/bar/{name}', '');
        $route->setSchemes(['https']);
        $routes->add($route);
        $context = RequestContext::create();
        $context->setScheme('https');
        $this->assertEquals(['https'], $route->getSchemes());
        $this->assertEquals('https', $context->getScheme());
        $matcher = new Matcher($context);
        $this->assertEquals($route, $matcher->match('/foo/100/bar/steven', $routes));
        $context->setScheme('http');
        $this->setExpectedExceptionRegExp('Slince\Routing\Exception\RouteNotFoundException');
        $matcher->match('/foo/100/bar/steven', $routes);
    }
}