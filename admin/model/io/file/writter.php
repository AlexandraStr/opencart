<?php
/**
 * Created by PhpStorm.
 * User: alexandra
 * Date: 06.05.18
 * Time: 21:08
 */


class ModelIoFileWritter extends Model
{
    const DEFAULT_FILE_MODE = self::MODE_WRITE_NEW;
    const MODE_WRITE_NEW = 'w';
    const MODE_WRITE_APPEND = 'a';

    protected $_fileHandle = null;

    protected $_isLocked = false;

    protected $_fileMode = self::DEFAULT_FILE_MODE;

    protected function _validateIsOpen()
    {
        if (!$this->isOpened()) {
            throw new Exception('File has to be opened first');
        }
        return true;
    }

    public function setWriteNew()
    {
        $this->_fileMode = self::MODE_WRITE_NEW;
        return $this;
    }

    public function setWriteAppend()
    {
        $this->_fileMode = self::MODE_WRITE_APPEND;
        return $this;
    }

    public function open($fileName)
    {
        if (empty($fileName)) {
            throw new Exception('Please specify a file name');
        }

        $this->_fileHandle = @fopen($fileName, $this->_fileMode);

        if (!$this->_fileHandle) {
            throw new Exception(
                sprintf('Unable to open the file "%s"', $fileName)
            );
        }

        return $this;
    }

    public function close()
    {
        if ($this->_fileHandle) {
            if ($this->_isLocked) {
                $this->release();
            }
            fclose($this->_fileHandle);
            $this->_fileHandle = null;
        }

        return $this;
    }

    public function isLocked()
    {
        return $this->_isLocked;
    }

    public function isOpened()
    {
        return !is_null($this->_fileHandle);
    }

    public function lock()
    {
        if (!$this->_isLocked && $this->isOpened()) {
            flock($this->_fileHandle, LOCK_EX);
        }

        return $this;
    }

    public function release()
    {
        if (!$this->_isLocked && $this->isOpened()) {
            flock($this->_fileHandle, LOCK_UN);
        }

        return $this;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function write($data)
    {
        $this->_validateIsOpen();
        $this->lock();
        try {
            fwrite($this->_fileHandle, $data);
            $this->release();
        } catch (Exception $e) {
            $this->release();
            throw $e;
        }

        return $this;
    }

    public function writeline($data)
    {
        return $this->write(
            sprintf('%s' . PHP_EOL, $data)
        );
    }

    public function getInstance()
    {
        return $this;
    }
}