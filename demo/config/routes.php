<?php
use Slince\Router\RouteCollection;

return function(RouteCollection $routes){
    $routes->http('/', 'Pages@index');
    $routes->http('/show', 'Pages@show');
};