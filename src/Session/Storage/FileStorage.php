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

    /**
     * session文件保存位置
     * @var string
     */
    protected $savePath;

    /**
     * 写入session时hasher
     *
     * @var string|callable
     */
    protected $hasher;

    /**
     * session文件名后缀
     *
     * @var string
     */
    protected $suffix = 'sess';

    /**
     * file handler
     * @var Filesystem
     */
    protected $filesystem;

    function __construct($savePath)
    {
        $savePath = trim($savePath, '\\/') . '/';
        $this->filesystem = new Filesystem();
        if (!file_exists($savePath)) {
            $this->filesystem->mkdir($savePath);
        }
        if (! is_dir($savePath)) {
            throw new SessionException(sprintf('Directory "%s" is not valid', $savePath));
        }
        $this->savePath = $savePath;
    }

    /**
     * 方法继承
     * @param string $savePath
     * @param string $sessionId
     * @return bool
     */
    function open($savePath, $sessionId)
    {
        return true;
    }

    /**
     * 方法继承
     * @param string $sessionId
     * @return string
     */
    function read($sessionId)
    {
        return (string)@file_get_contents($this->getSessionFile($sessionId));
    }

    /**
     * 方法继承
     * @param string $sessionId
     * @param string $sessionData
     * @return bool|void
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
     * 方法继承
     * @param string $sessionId
     * @return bool|void
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
     * 方法继承
     * @param int $maxlifetime
     * @return bool
     */
    function gc($maxlifetime)
    {
        $result = true;
        try {
            foreach (glob("{$this->savePath}*_{$this->suffix}") as $file) {
                if(time() - filemtime($file) > $maxlifetime) {
                    $this->filesystem->remove($file);
                }
            }
        } catch (IOException $e) {
            $result = false;
        }
        return $result;
    }

    /**
     * 方法继承
     * @return bool
     */
    function close()
    {
        return true;
    }

    /**
     * 获取session文件保存路径
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
     * @param string $sessionId
     * @return string
     */
    function getSessionFile($sessionId)
    {
        return "{$this->savePath}{$sessionId}_{$this->suffix}";
    }

    /**
     * 获取session文件名后缀
     * @return string
     */
    function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * 设置session文件名后缀
     * @param $suffix string
     */
    function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }
}