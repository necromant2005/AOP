<?php
namespace Aop\Loader\Stream\Wrapper;

use Aop\Loader\Stream as LoaderStream;

class Assert implements LoaderStream\Wrapper
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

    public function replace($match)
    {
        $methodName = $match[1];
        $arguments = (array)explode(',', $match[2]);
        $processedArguments = array();
        $asserts = array();
        foreach ($arguments as $position=>$argument) {
            $argument = trim($argument);
            if (!strpos($argument, ' ')) {
                $processedArguments = $argument;
                continue;
            }
            list($type, $name) = explode(' ', $argument);
            $validator = $this->_map[$type];
            $asserts[] = '        if (!' . $validator . '(' . $name . ')) throw new \ArgumentError(\'Argument '
                . $position . ' passed to ' . $methodName . '() must be a ' . $type . '\');';
            $processedArguments[] = $name;
        }
        return 'public function ' . $methodName
            . '('.join(', ', $processedArguments).')'
            . PHP_EOL . '    {' . PHP_EOL
            . join(PHP_EOL, $asserts) . PHP_EOL . '    ';
    }
}

