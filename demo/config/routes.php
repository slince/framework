<?php
use Slince\Routing\RouteCollection;

return function(RouteCollection $routes) {
    $routes->http('/', 'Default@PagesController@index');
    $routes->http('/{id}', 'Default@PagesController@show')
        ->setRequirement('id', '\w+');
};