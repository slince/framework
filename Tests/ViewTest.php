<?php
use Slince\View\ServiceFactory;

class ViewTest extends PHPUnit_Framework_TestCase
{

    function _getService()
    {
        return ServiceFactory::get('native', [
            'viewPath' => __DIR__ . '/views/',
            'elementPath' => __DIR__ . '/elements/',
            'layoutPath' => __DIR__ . '/layouts/',
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
        $this->assertNotEmpty($content);
    }
    
    function testElement()
    {
        $service = $this->_getService();
        $view = $service->load('view3');
        $content = $view->render();
        $this->assertNotEmpty($content);
    }

    function testLayout()
    {
        $service = $this->_getService();
        $view = $service->load('view2', 'default');
        $content = $view->render([
            'hello' => 'hello'
        ]);
        $this->assertNotEmpty($content);
    }
}