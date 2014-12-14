<?php
namespace Slince\Session\Handler;

class FileHandler extends \SessionHandler
{
    function __construct()
    {
        
    }
    
    function open()
    {
        return true;
    }
    
    function read($session_id)
    {
        
    }
}