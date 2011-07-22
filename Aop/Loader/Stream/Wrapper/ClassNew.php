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
        return preg_replace_callback('~^(.*?)=\s+([a-zA-Z0-9_]+)\.new\(([^\)]*)\)+~m',
            array($this, 'replace'), $content);
    }

    public function replace($match)
    {
        $before = trim($match[1]);
        $className = $match[2];
        $arguments = $match[3];
        return '$object' . $className . ' = new ' . $className
            . '(' . $arguments . ');' . PHP_EOL
            . $before . ' = ' . '$object' . $className;
    }
}

