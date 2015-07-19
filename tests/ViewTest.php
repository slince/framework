<?php
use Slince\View\ServiceFactory;

class ViewTest extends PHPUnit_Framework_TestCase
{

    function _getService()
    {
        return ServiceFactory::get('native', [
            'viewPath' => __DIR__ . '/views/'
        ]);
    }
    
    function testView()
    {
        $service = $this->_getService();
        $view = $service->load('view1');
        $content = $view->render();
        $this->assertNotEmpty($content);
    }

}