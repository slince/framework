<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\File;

abstract class AbstractFile implements FileInterface
{

    /**
     * 文件类型
     * @var string
     */
    const FILE_TYPE = '';

    /**
     * 文件地址
     *
     * @var string
     */
    protected $_path;

    function __construct($path = '')
    {
        $this->_path = $path;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\FileInterface::setPath()
     */
    function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\FileInterface::getPath()
     */
    function getPath()
    {
        if (! is_file($this->_path)) {
            throw new \Exception(sprintf("File does not exist!", $this->_path));
        }
        return $this->_path;
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Config\File\FileInterface::getPathWithoutException()
     */
    function getPathWithoutException()
    {
        return $this->_path;
    }
}