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
    protected $parsers = [];

    /**
     * 支持的解析器类型
     * 
     * @var array
     */
    protected $supportFileParsers = [
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
     * 加载配置文件或者目录
     * @param string|array $path
     * @return $this
     */
    function load($path)
    {
        $paths = is_array($path) ? $path : [
            $path
        ];
        foreach ($paths as $path) {
            $this->data = array_merge($this->data, $this->parse($path));
        }
        return $this;
    }

    /**
     * 解析一个配置文件或者配置目录
     * @param string|array $path
     * @return array
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
     * 将配置数据静态化到一个配置文件
     * @param string $filePath
     * @return boolean
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
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');
            if (empty($paths)) {
                throw new InvalidFileException(sprintf('Directory "%s" is empty', $path));
            }
        } else {
            if (!file_exists($path)) {
                throw new InvalidFileException(sprintf('File "%s" cannot be found', $path));
            } else {
                $paths = [$path];
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
        //如果已经生成直接返回
        if (isset($this->parsers[$extension])) {
            return $this->parsers[$extension];
        }
        foreach ($this->supportFileParsers as $fileParser) {
            if (in_array($extension, call_user_func([$fileParser, 'getSupportedExtensions']))) {
                $this->parsers[$extension] = new $fileParser();
                return $this->parsers[$extension];
            }
        }
        throw new UnsupportedFormatException('Unsupported configuration format');
    }
}