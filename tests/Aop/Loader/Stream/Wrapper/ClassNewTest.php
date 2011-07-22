<?php
namespace Aop\Loader\Stream\Wrapper;

class ClassNewTest extends \PHPUnit_Framework_TestCase
{
    public function testAsserts()
    {
        $wrapper = new ClassNew();
        $this->assertEquals(
            file_get_contents(__DIR__ . '/_files/ClassNew-origin.php'),
            $wrapper->process(file_get_contents(__DIR__ . '/_files/ClassNew.php')));
    }
}

