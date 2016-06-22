<?php
namespace Slince\Routing\Tests;

use Slince\Routing\Factory;
use Slince\Routing\RequestContext;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    function testCreate()
    {
        $this->assertInstanceOf('Slince\Routing\Matcher', Factory::createMatcher());
        $this->assertInstanceOf('Slince\Routing\Generator', Factory::createGenerator(RequestContext::create()));
        $this->assertInstanceOf('Slince\Routing\RouteCollection', Factory::createRoutes());
    }
}