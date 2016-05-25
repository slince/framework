<?php
namespace Slince\Routing\Tests;

use Slince\Routing\Generator;
use Slince\Routing\RequestContext;
use Slince\Routing\Route;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    function testSimpleGenerate()
    {
        $route = new Route('/path', '');
        $generator = new Generator(RequestContext::create());
        $this->assertEquals('http://localhost/path', $generator->generate($route));
    }

    function testGenerateWithRegex()
    {
        $route = new Route('/users/{id}', '');
        $generator = new Generator(RequestContext::create());
        $this->assertEquals('http://localhost/users/', $generator->generate($route));
        $this->assertEquals('http://localhost/users/100', $generator->generate($route, ['id' => 100]));
        $this->assertEquals('http://localhost/users/steven', $generator->generate($route, ['id' => 'steven']));
    }

    function testGenerateException()
    {
        $route = new Route('/users/{id}', '');
        $generator = new Generator(RequestContext::create());
        $generator->setStrictRequirements(true);
        $this->setExpectedExceptionRegExp('\Slince\Routing\Exception\InvalidArgumentException');
        $generator->generate($route);
    }

    function testGenerateExceptionInvalidType()
    {
        $route = new Route('/users/{id}', '');
        $route->setRequirement('id', '\d+');
        $generator = new Generator(RequestContext::create());
        $generator->setStrictRequirements(true);
        $this->setExpectedExceptionRegExp('\Slince\Routing\Exception\InvalidArgumentException');
        echo $generator->generate($route, ['id' => 'steven']);
    }

    function testGenerateAdvanced()
    {
        
    }
}