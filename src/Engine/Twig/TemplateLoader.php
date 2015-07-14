<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Twig;

use Slince\View\ViewManagerInterface;

class TemplateLoader implements \Twig_LoaderInterface
{
    protected $_viewManager;
    
    function __construct(ViewManagerInterface $viewManager)
    {
        $this->_viewManager = $viewManager;
    }
    
    function getSource($name)
    {
        
    }
    
    protected function _getTemplate($name)
    {
        
    }
}