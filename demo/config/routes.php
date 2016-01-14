<?php
use Slince\Routing\RouteCollection;

return function(RouteCollection $routes) {
    $routes->http('/', 'Web@PagesController@index');
    $routes->http('/{id}', 'Web@PagesController@show')
        ->setRequirement('id', '\w+');
};