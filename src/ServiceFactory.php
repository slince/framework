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

    /**
     * 获得视图渲染服务
     * @param string $serviceName
     * @param array $configs
     * @throws \Exception
     * @return ViewManagerInterface
     */
    static function get($serviceName, $configs = [])
    {
        if (isset(self::$serviceMap[$serviceName])) {
            $service = '\\Slince\\View\\Engine\\' . self::$serviceMap[$serviceName] . '\\ViewManager';
        } else {
            throw new \Exception(sprintf("Service %s does not support", $serviceName));
        }
        return new $service($configs);
    }
}