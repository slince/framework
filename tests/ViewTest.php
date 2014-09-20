<?php
use Slince\View\View;
Class ViewTest extends PHPUnit_Framework_TestCase
{
    function testView()
    {
        $view = new View(__DIR__ . '/views/view1.php');
        $content = $view->render();
        $this->assertNotEmpty($content);
    }
    function testBlock()
    {
        $view = new View(__DIR__ . '/views/view2.php');
        $content = $view->render();
        $this->assertNotEmpty($content);
    }
}