<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing\Exception;

class MethodNotAllowedException extends \Exception
{
    /**
     * @var array
     */
    protected $_allowedMethods = array();

    function __construct(array $allowedMethods, $message = null, $code = 0, $previous = null)
    {
        $this->_allowedMethods = array_map('strtoupper', $allowedMethods);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Gets the allowed HTTP methods.
     *
     * @return array
     */
    function getAllowedMethods()
    {
        return $this->_allowedMethods;
    }
}
