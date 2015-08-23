<?php
use Slince\Di\Container;
use Slince\Router\RequestContext;
use Slince\Router\RouterFactory;

return [
    'router' => function(Container $container) {
         $context = RequestContext::create()->fromRequest($container->get('app')->getRequest());
         return RouterFactory::create($context);
    },
];