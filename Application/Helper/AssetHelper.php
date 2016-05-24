<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Helper;

class AssetHelper extends Helper
{
    protected $baseUrl = '/';
    
    function getBaseUrl()
    {
        return $this->baseUrl;
    }
    
    function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }
    
    function url($assetPath)
    {
        return "{$this->baseUrl}{$assetPath}";
    }
}