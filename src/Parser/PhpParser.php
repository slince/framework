<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;
use Slince\Config\FileInterface;

class PhpParser extends AbstractParser
{
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::parse()
     */
    function parse(FileInterface $file)
    {
        $data = include $file->getPath();
        if (! is_array($data)) {
            throw new ParseException(sprintf('The file "%s" must return a PHP array', $file->getPath()));
        }
        return $data;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::dump()
     */
    function dump(FileInterface $file, array $data)
    {
        $string = "<?php\r\nreturn " . var_export($data, true) . ";\r\n";
        return @file_put_contents($file->getPath(), $string) !== false;
    }
}