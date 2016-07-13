<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

interface ApplicationInterface
{

    /**
     * 获取application名称
     * @return string
     */
    public function getName();
    
    /**
     * 设置application名称
     * @param string $name
     */
    public function setName($name);
    
    /**
     * 获取当前application的命名空间
     * @return string
     */
    public function getNamespace();
    
    /**
     * 获取kernel
     * @return Kernel
     */
    public function getKernel();

    /**
     * 运行app
     * @param Kernel $kernel
     * @param string $controller
     * @param string $action
     * @param array $parameters
     */
    public function run(Kernel $kernel, $controller, $action, $parameters);
}