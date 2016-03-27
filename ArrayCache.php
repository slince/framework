<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class ArrayCache extends AbstractCache
{

    private $data = [];

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doSet()
     */
    protected function doSet($key, $value, $duration)
    {
        $this->data[$key] = $value;
        return true;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doGet()
     */
    protected function doGet($key)
    {
        return $this->doExists($key) ? $this->data[$key] : false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doGet()
     */
    protected function doExists($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doDelete()
     */
    protected function doDelete($key)
    {
        unset($this->data[$key]);
        return true;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doFlush()
     */
    protected function doFlush()
    {
        $this->data = [];
        return true;
    }
}