<?php
use Slince\View\ServiceFactory;

class ViewTest extends PHPUnit_Framework_TestCase
{

    function _getService()
    {
        return ServiceFactory::get('native', [
            'viewPath' => __DIR__ . '/views/',
            'elementPath' => __DIR__ . '/elements/',
        ]);
    }
    
    function testView()
    {
        $service = $this->_getService();
        $view = $service->load('view1');
        $content = $view->render();
        $this->assertNotEmpty($content);
        $view = $service->load('view2');
        $content = $view->render([
            'hello' => 'hello'
        ]);
        $this->assertEquals('hello', $content);
    }
    
    function testElement()
    {
        $service = $this->_getService();
        $view = $service->load('view3');
        $content = $view->render();
        var_dump($content);
    }

}