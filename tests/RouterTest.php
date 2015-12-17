<?php
use Slince\Routing\Factory;
use Slince\Routing\RequestContext;
use Slince\Routing\RouteCollection;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    function testRouter()
    {
        $context = RequestContext::create();
        //$context->setHost('m.baidu.com');
        $router = RouterFactory::create($context);
        $routes = $router->getRoutes();
        $routes->http('/user/{id}', [
            'name' => 'home.dash',
            'action' => 'UsersController@dashboard'
        ])->setRequirements([
            'id' => '\d+'
        ]);
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
           $route = $router->match('/user/2');
//            print_r($route->getPathRegex());
           print_r($route->getRouteParameters());
           //echo $router->generate($route, ['id' => 3]);
        } catch (\Exception $e) {
            throw $e;
        }
//         echo $router->generateByAction('/UsersController@dashboard', [], true);
//         echo $router->generateByName('home.dash', ['a'=>'b'], true);
    }
}