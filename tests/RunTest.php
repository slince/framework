<?php
class RunTest extends \PHPUnit_Framework_TestCase
{
    function testBase()
    {
        $stack = array();
        return $stack;
    }
    /**
     * @test
     * @depends testBase
     */
    function A(array $stack)
    {
        $this->assertEmpty($stack);
        array_push($stack, 1);
        $this->assertEquals(1, count($stack));
        $this->assertFalse(count($stack)==1);
    }
}