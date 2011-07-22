<?php
namespace Aop\Loader\Stream\Wrapper;

use Aop\Loader\Stream as LoaderStream;

class ClassNew implements LoaderStream\Wrapper
{
    private $_map = array(
        'boolean' => 'is_bool',
        'integer' => 'is_integer',
        'string' => 'is_string',
    );

    public function process($content)
    {
        return preg_replace_callback('~public function ([a-zA-Z0-9\_]+)\(([^\)]+)\)\s*\{\s+~si',
            array($this, 'replace'), $content);
    }
}

