<?php
namespace Slince\Session\Bridge;

use Slince\Session\BridgeInterface;
use Slince\Session\SessionManager;

class CookieBridge implements BridgeInterface
{

    private $_cookieParams = [
        'lifetime' => 0,
        'path' => '',
        'domain' => '',
        'secure' => false,
        'httponly' => false
    ];

    /**
     * 是否已经从ini文件上读取过配置
     *
     * @var boolean
     */
    private $_hasReadFromIniFile = false;

    function setCookieParam($name, $param)
    {
        if (! $this->_hasReadFromIniFile) {
            $this->_cookieParams = session_get_cookie_params();
        }
        $this->_cookieParams[$name] = $param;
    }

    function setCookieParams($params)
    {
        if (! $this->_hasReadFromIniFile) {
            $this->_cookieParams = session_get_cookie_params();
        }
        $this->_cookieParams = array_merge($this->_cookieParams, $params);
    }

    function init(SessionManager $sessionManager)
    {
        if ($this->_hasReadFromIniFile) {
            call_user_func_array('session_set_cookie_params', $this->_cookieParams);
        }
        return true;
    }
}