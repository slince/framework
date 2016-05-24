<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Bridge;

use Slince\Session\SessionManager;

class CookieBridge implements BridgeInterface
{

    private $_cookieParameters = [
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

    /**
     * 设置cookie参数
     * 
     * @param string $name
     * @param string $parameter
     */
    function setCookieParameter($name, $parameter)
    {
        if (! $this->_hasReadFromIniFile) {
            $this->_cookieParameters = session_get_cookie_Parameters();
        }
        $this->_cookieParameters[$name] = $parameter;
    }

    /**
     * 批量设置参数
     * 
     * @param array $parameters
     */
    function setCookieParameters($parameters)
    {
        if (! $this->_hasReadFromIniFile) {
            $this->_cookieParameters = session_get_cookie_Parameters();
        }
        $this->_cookieParameters = array_merge($this->_cookieParameters, $parameters);
    }

    /**
     * 初始化
     * 
     * @param SessionManager $sessionManager
     * @return boolean
     */
    function init(SessionManager $sessionManager)
    {
        if ($this->_hasReadFromIniFile) {
            call_user_func_array('session_set_cookie_Parameters', $this->_cookieParameters);
        }
        return true;
    }
}