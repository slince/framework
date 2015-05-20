<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

interface FileInterface
{

    const FILE_TYPE = '';
    
    function getPath();

    function setPath();
    
}