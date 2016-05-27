<?php
namespace Slince\Routing\Tests;

use Slince\Routing\Route;
use Slince\Routing\RouterFactory;
use Slince\Routing\RequestContext;
use Slince\Routing\RouteCollection;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    function testCreateRouter()
    {
        $this->assertInstanceOf('Slince\Routing\Router', RouterFactory::create());
    }

    function testMatch()
    {
        $router = RouterFactory::create();
        $routes = $router->getRoutes();
        $routes->add(new Route('/path', ''));
        $this->assertEquals('/path', $router->match('/path')->getPath());
    }

    function testGenerate()
    {
        $context = RequestContext::create();
        $context->setHost('m.domain.com');
        $router = RouterFactory::create($context);
        $routes = $router->getRoutes();
        $routes->add(new Route('/path', ''));
        $routes->http('/users/{id}', [
                'name' => 'home.dash',
                'action' => 'UsersController@dashboard'
            ])->setRequirements([
                'id' => '\d+',
                'subdomain' => 'm'
            ])->setHost('{subdomain}.domain.com')
            ->setDefaults(['subdomain' => 'm']);
        $routes->prefix('user', function (RouteCollection $routes) {
            $routes->http('/messages', 'MessagesController@index');
            $routes->http('/messages/{id}', 'MessagesController@show');
            $routes->prefix('me', function (RouteCollection $routes) {
                $routes->http('/account', 'UsersController@me');
            });
        });
        $routes->prefix('admin', function (RouteCollection $routes) {
            $routes->http('/dashboard/', 'HomeController@index');
        });
        $this->assertEquals('http://www.domain.com/users/100', $router->generateByName('home.dash', ['subdomain' => 'www', 'id' => 100], true));
        $this->assertEquals('http://m.domain.com/user/messages/100', $router->generateByAction('MessagesController@show', ['subdomain' => 'www', 'id' => 100], true));
        $this->assertEquals('/user/me/account', $router->generateByAction('UsersController@me'));
        $this->assertEquals('/admin/dashboard', $router->generateByAction('HomeController@index'));
    }
}