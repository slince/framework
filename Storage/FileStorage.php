<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Storage;

use Slince\Session\Exception\SessionException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class FileStorage extends AbstractStorage
{

    protected $savePath;

    /**
     * file handler
     * 
     * @var Filesystem
     */
    protected $filesystem;

    function __construct($savePath)
    {
        if (! is_dir($savePath)) {
            throw new SessionException(sprintf('Directory "%s" is not valid', $savePath));
        }
        $this->savePath = $savePath;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \SessionHandlerInterface::open()
     */
    function open($savePath, $sessionName)
    {
        $this->filesystem = new Filesystem();
        return true;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \SessionHandlerInterface::read()
     */
    function read($sessionId)
    {
        return (string)@file_get_contents($this->getSessionFile($sessionId));
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \SessionHandlerInterface::write()
     */
    function write($sessionId, $sessionData)
    {
        try {
            $result = $this->filesystem->dumpFile($this->getSessionFile($sessionId), $sessionData);
        } catch (IOException $e) {
            $result = false;
        }
        return $result;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \SessionHandlerInterface::destroy()
     */
    function destroy($sessionId)
    {
        try {
            $result = $this->filesystem->remove($this->getSessionFile($sessionId));
        } catch (IOException $e) {
            $result = false;
        }
        return $result;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \SessionHandlerInterface::gc()
     */
    function gc($maxlifetime)
    {
        try {
            $result = $this->filesystem->remove($this->savePath);
        } catch (IOException $e) {
            $result = false;
        }
        return $result;
    }

    /**
     * @see \SessionHandlerInterface::close();
     */
    function close()
    {
        $this->filesystem = null;
    }

    /**
     * 获取session文件保存路径
     *
     * @return string
     */
    function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * 获取filesystem
     * @return Filesystem
     */
    function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * 获取session文件路径
     * 
     * @param string $sessionId            
     * @return string
     */
    protected function getSessionFile($sessionId)
    {
        return "{$this->savePath}{$sessionId}";
    }
}