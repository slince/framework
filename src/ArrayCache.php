<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class ArrayCache extends AbstractCache
{

    private $_data = [];

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doSet()
     */
    protected function _doSet($key, $value, $duration)
    {
        $this->_data[$key] = $value;
        return true;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doGet()
     */
    protected function _doGet($key)
    {
        return $this->_doExists($key) ? $this->_data[$key] : false;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doGet()
     */
    protected function _doExists($key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doDelete()
     */
    protected function _doDelete($key)
    {
        unset($this->_data[$key]);
        return true;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doFlush()
     */
    protected function _doFlush()
    {
        $this->_data = [];
        return true;
    }
}