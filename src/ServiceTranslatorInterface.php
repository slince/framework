<?php
namespace Slince\Applicaion;

interface ServiceTranslatorInterface
{
    /**
     * 将服务创建配置格式解析
     * 
     * @param mixed $config
     */
    function translate($name, $config);
}