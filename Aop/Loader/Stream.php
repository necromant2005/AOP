<?php
namespace Aop\Loader;

class Stream
{
    private static $_wrappers = array();

    /**
     * Current stream position.
     *
     * @var int
     */
    protected $_pos = 0;

    /**
     * Data for streaming.
     *
     * @var string
     */
    protected $_data;

    /**
     * Stream stats.
     *
     * @var array
     */
    protected $_stat;

    /**
     * Opens the script file and converts markup.
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        // get the view script source
        $path        = str_replace('zend.view://', '', $path);
        $this->_data = file_get_contents($path);

        /**
         * If reading the file failed, update our local stat store
         * to reflect the real stat of the file, then return on failure
         */
        if ($this->_data === false) {
            $this->_stat = stat($path);
            return false;
        }

        /**
         * Call callbacks
         *
         */
        foreach (self::getWrappers() as $wrapper) {
            $this->_data = $wrapper->process($this->_data);
        }

        /**
         * file_get_contents() won't update PHP's stat cache, so we grab a stat
         * of the file to prevent additional reads should the script be
         * requested again, which will make include() happy.
         */
        $this->_stat = stat($path);

        return true;
    }

    /**
     * Included so that __FILE__ returns the appropriate info
     *
     * @return array
     */
    public function url_stat()
    {
        return $this->_stat;
    }

    /**
     * Reads from the stream.
     */
    public function stream_read($count)
    {
        $ret = substr($this->_data, $this->_pos, $count);
        $this->_pos += strlen($ret);
        return $ret;
    }


    /**
     * Tells the current position in the stream.
     */
    public function stream_tell()
    {
        return $this->_pos;
    }


    /**
     * Tells if we are at the end of the stream.
     */
    public function stream_eof()
    {
        return $this->_pos >= strlen($this->_data);
    }


    /**
     * Stream statistics.
     */
    public function stream_stat()
    {
        return $this->_stat;
    }


    /**
     * Seek to a specific point in the stream.
     */
    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($this->_data) && $offset >= 0) {
                $this->_pos = $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->_pos += $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_END:
                if (strlen($this->_data) + $offset >= 0) {
                    $this->_pos = strlen($this->_data) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }

    public static function getWrappers()
    {
        return self::$_wrappers;
    }

    public static function registerWrapper(Stream\Wrapper $wrapper)
    {
        self::$_wrappers[] = $wrapper;
    }
}

