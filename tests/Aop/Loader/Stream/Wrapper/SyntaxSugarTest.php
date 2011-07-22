<?php
namespace Aop\Loader\Stream\Wrapper;

class SyntaxSugarWrapperTest extends \PHPUnit_Framework_TestCase
{
    public function testAsserts()
    {
        $wrapper = new SyntaxSugar();
        $this->assertEquals(
            file_get_contents(__DIR__ . '/_files/Asserts-origin.php'),
            $wrapper->process(file_get_contents(__DIR__ . '/_files/Asserts.php')));
    }
}

