<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

use Slince\Config\Exception\UnsupportedFormatException;
use Slince\Config\Exception\InvalidFileException;

class Config implements ConfigInterface
{

    use DataObjectTrait;
    
    protected $_parsers = [];
    
    protected $_supportFileParsers = [
        'Slince\\Config\\Parser\\PhpParser',
        'Slince\\Config\\Parser\\IniParser',
        'Slince\\Config\\Parser\\JsonParser',
    ];
    
    function __construct($paths = null)
    {
        is_array($paths) || $paths = [$paths];
        foreach ($paths as $path) {
            $this->_data = array_merge($this->_data, $this->load($path));
        }
    }
    
    function load($path)
    {
        $data = [];
        $files = $this->getFilePath($path);
        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION); 
            $data = array_merge($data, $this->getParser($extension)->parse()); 
        }
        return $data;
    }
    
    /**
     * 根据
     * @param unknown $path
     * @throws InvalidFileException
     * @return Ambigous <multitype:, multitype:unknown >
     */
    function getFilePath($path)
    {
        $paths = [];
        if (! is_file($path)) {
            throw new InvalidFileException(sprintf('File "%s" cannot be found', $path));
        } else {
            $paths = [$path];
        }
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');
            if (empty($paths)) {
                throw new InvalidFileException(sprintf('Directory "%s" is empty', $path));
            }
        }
        return $paths;
    }
    
    function getParser($extension)
    {
        $parser = null;
        foreach ($this->_supportFileParsers as $fileParser) {
            if (in_array($extension, $fileParser::getSupportedExtensions)) {
                if (! isset($this->_parsers[$fileParser])) {
                    $this->_parsers[$fileParser] = new $fileParser;
                }
                return $this->_parsers[$fileParser];
            }
        }
        throw new UnsupportedFormatException('Unsupported configuration format');
    }
}