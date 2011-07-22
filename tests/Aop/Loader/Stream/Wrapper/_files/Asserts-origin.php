<?php

class Asserts
{
    public function foo($content, $lenght)
    {
        if (!is_string($content)) throw new \ArgumentError('Argument 0 passed to foo() must be a string');
        if (!is_integer($lenght)) throw new \ArgumentError('Argument 1 passed to foo() must be a integer');
    }

    public function _boo($flag)
    {
        if (!is_bool($flag)) throw new \ArgumentError('Argument 0 passed to _boo() must be a boolean');
    }
}

