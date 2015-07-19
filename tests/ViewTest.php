<?php
use Slince\View\ServiceFactory;

class ViewTest extends PHPUnit_Framework_TestCase
{

    function _getService()
    {
        return ServiceFactory::get('native ');
    }
    
    function testView()
    {
        $view = new View(__DIR__ . '/views/view1.php');
        $content = $view->render();
        $this->assertNotEmpty($content);
        $this->assertEquals($content, 'hello');
    }

    function testVars()
    {
        $view = new View(__DIR__ . '/views/view2.php');
        $view->setVar('hello', 'hello');
        $content = $view->render();
        ;
        $this->assertEquals($content, 'helloworld;');
    }
}