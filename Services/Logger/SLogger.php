<?php

/**
 * Service that can be used to write new log or push message in a specified logfile (/var/log/filename.log)
 * 
 * @author Niaina Michaël RAZAFIMANDIMBY <https://github.com/balzacLeGeek>
 */
namespace balzacLeGeek\BotbundBundle\Services\Logger;

use balzacLeGeek\BotbundBundle\Services\Logger\LoggerInterface;
use balzacLeGeek\BotbundBundle\Utils\Config;
use Monolog\Logger as MonologLoger;
use Monolog\Handler\StreamHandler;

class SLogger implements LoggerInterface 
{
    static $logFile = Config::LOG_FILE;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * SLogger constructor
     *
     * @param string $rootDir
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * Write new log or push message to logfile
     *
     * @param string $content
     * @param string|null $logFile
     * @param boolean $pushMessage
     * @param string $messageLevel
     * @return Logger
     */
    public function writeLog(string $content, ?string $logFile = null, bool $pushMessage = false, string $messageLevel = MonologLoger::NOTICE): SLogger
    {
        $logFile = self::$logFile ?: $logFile;

        if (!$logFile) {
            throw new \InvalidArgumentException("Log file name must be a type of string, NULL given");
        }

        $logFilePath = $this->rootDir . '/var/log/' . $logFile;
        $logger = new MonologLoger($logFile);

        $logger->pushHandler(new StreamHandler($logFilePath, $messageLevel));

        // Add header if itsn't a message push
        if (!$pushMessage) {
            $logger->notice('------------------------[' . date("Y-m-d H:i:s") . ']------------------------');
        }

        $logger->notice($content);
        $logger->notice('---------------------------------------------------------------------');

        return $this;
    }
}