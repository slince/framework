<?php
use Slince\Routing\RouterFactory;
use Slince\Routing\RequestContext;
use Slince\Routing\RouteCollection;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    function testRouter()
    {
        $context = RequestContext::create();
        $context->setHost('m.baidu.com');
        $router = RouterFactory::create($context);
        $routes = $router->getRoutes();
        $route = $routes->http('/users/{id}', [
            'name' => 'home.dash',
            'action' => 'UsersController@dashboard'
        ])->setRequirements([
            'id' => '\d+',
            'subdomain' => 'm'
        ])->setHost('{subdomain}.baidu.com')
        ->setDefaults(['subdomain' => 'm']);
        /**
        $routes->prefix('user', function(RouteCollection $routes){
            $routes->http('/users', 'UsersController@index');
            $routes->http('/users/{id}', 'UsersController@home')
                ->setRequirements(['id'=>'\d+', 'subdomain' => '((www|m).)?', 'maindomain'=>'baidu'])
                ->setDomain('{subdomain}{maindomain}.com');
            $routes->prefix('me', function (RouteCollection $routes) {
                $routes->http('/account', 'UsersController@me');
            });
        });**/
        try {
           $route = $router->match('/users/1256');
//            print_r($route->getRouteParameters());
        } catch (\Exception $e) {
            throw $e;
        }
        echo $router->generateByAction('UsersController@dashboard', [
            'id' => 1265
        ], true);
//         echo $router->generateByName('home.dash', ['a'=>'b'], true);
    }
}