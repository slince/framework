<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

use Slince\Config\Exception\UnsupportedFormatException;
use Slince\Config\Exception\InvalidFileException;
use Slince\Config\Parser\ParserInterface;

class Config extends DataObject implements ConfigInterface
{

    /**
     * 解析器实例
     *
     * @var array
     */
    protected $_parsers = [];

    /**
     * 支持的解析器类型
     * 
     * @var array
     */
    protected $_supportFileParsers = [
        'Slince\\Config\\Parser\\PhpParser',
        'Slince\\Config\\Parser\\IniParser',
        'Slince\\Config\\Parser\\JsonParser'
    ];

    function __construct($path = null)
    {
        if (! is_null($path)) {
            $this->load($path);
        }
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ConfigInterface::load()
     */
    function load($path)
    {
        $paths = is_array($path) ? $path : [
            $path
        ];
        foreach ($paths as $path) {
            $this->_data = array_merge($this->_data, $this->parse($path));
        }
        return $this;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ConfigInterface::parse()
     */
    function parse($path)
    {
        $data = [];
        $filePaths = $this->getFilePath($path);
        foreach ($filePaths as $filePath) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $data = array_merge($data, $this->getParser($extension)->parse($filePath));
        }
        return $data;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ConfigInterface::dump()
     */
    function dump($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $parser = $this->getParser($extension);
        return $parser->dump($filePath, $this->toArray());
    }

    /**
     * 获取配置文件或者配置目录下的合法文件
     * 
     * @param string|array $path            
     * @throws InvalidFileException
     * @return array
     */
    function getFilePath($path)
    {
        $paths = [];
        if (! is_file($path)) {
            throw new InvalidFileException(sprintf('File "%s" cannot be found', $path));
        } else {
            $paths = [
                $path
            ];
        }
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');
            if (empty($paths)) {
                throw new InvalidFileException(sprintf('Directory "%s" is empty', $path));
            }
        }
        return $paths;
    }

    /**
     * 根据扩展名获取文件解析器
     * 
     * @param string $extension            
     * @throws UnsupportedFormatException
     * @return ParserInterface
     */
    function getParser($extension)
    {
        $parser = null;
        foreach ($this->_supportFileParsers as $fileParser) {
            if (in_array($extension, call_user_func([
                $fileParser,
                'getSupportedExtensions'
            ]))) {
                if (! isset($this->_parsers[$fileParser])) {
                    $this->_parsers[$fileParser] = new $fileParser();
                }
                return $this->_parsers[$fileParser];
            }
        }
        throw new UnsupportedFormatException('Unsupported configuration format');
    }
}