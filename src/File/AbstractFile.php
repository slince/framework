<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\File;

use Slince\Config\FileInterface;

abstract class AbstractFile implements FileInterface
{

    /**
     * 解析器条目
     *
     * @var string
     */
    protected $_path;

    function __construct($path = '')
    {
        $this->_path = $path;
    }

    function setPath($path)
    {
        $this->_path = $path;
    }

    function getPath()
    {
        if (! is_file($this->_path)) {
            throw new \Exception(sprintf("File does not exist!", $this->_path));
        }
        return $this->_path;
    }
}