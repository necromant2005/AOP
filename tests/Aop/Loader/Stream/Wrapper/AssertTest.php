<?php
namespace Aop\Loader\Stream\Wrapper;

class AssertTest extends \PHPUnit_Framework_TestCase
{
    public function testAsserts()
    {
        $wrapper = new Assert();
        $this->assertEquals(
            file_get_contents(__DIR__ . '/_files/Asserts-origin.php'),
            $wrapper->process(file_get_contents(__DIR__ . '/_files/Asserts.php')));
    }
}

