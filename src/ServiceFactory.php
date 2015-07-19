<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

class ServiceFactory
{

    const SERVICE_NATIVE = 'native';

    const SERVICE_TWIG = 'twig';

    static $serviceMap = [
        self::SERVICE_NATIVE => 'Native',
        self::SERVICE_TWIG => 'Twig'
    ];

    static function get($serviceName)
    {
        if (isset(self::$serviceMap[$serviceName])) {
            $service = "\\Slince\\View\\" . self::$serviceMap[$serviceName] . 'ViewManager';
        } else {
            throw new \Exception(sprintf("Service %s does not support", $serviceName));
        }
        return new $service();
    }
}