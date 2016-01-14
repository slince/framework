<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

interface ApplicationInterface
{

    public function getKernel();

    public function run(Kernel $kernel, $contollerName, $action, $parameters);
}