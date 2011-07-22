<?php
namespace Aop;

class Loader
{
    const PROTOCOL = 'aop';

    public function registerStream()
    {
        if ($this->hasStream()) return false;
        stream_wrapper_register(self::PROTOCOL, 'Aop\\Loader\\Stream');
    }

    public function hasStream()
    {
        return in_array(self::PROTOCOL, stream_get_wrappers());
    }

    public static function load($className)
    {
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        if ($this->hasStream()) {
            include_once self::PROTOCOL . '://' . $fileName;
        } else {
            include_once $fileName;
        }
    }
}

