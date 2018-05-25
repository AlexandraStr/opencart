<?php
/**
 * Created by PhpStorm.
 * User: alexandra
 * Date: 06.05.18
 * Time: 21:31
 */

class ModelIoFileLogger extends ModelIoFileWritter
{
    protected $_logFileName = null;

    public function setLogFileName($logFileName)
    {
        $this->_logFileName = $logFileName;
        return $this;
    }

    public function write($logMessage)
    {
        $this->setWriteAppend()->open($this->_logFileName);
        try {
            parent::write(
                sprintf(
                    '[%s]: %s' . PHP_EOL,
                    date('Y-m-d H:i:s'),
                    $logMessage
                )
            );
        } catch (Exception $e) {
            $this->close();
            throw  $e;
        }


        return $this;
    }
}