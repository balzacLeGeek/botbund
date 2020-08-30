<?php

namespace balzacLeGeek\BotbundBundle\Services\Logger;

use Monolog\Logger as MonologLoger;

interface LoggerInterface {

    public function writeLog(string $content, ?string $logFile = null, bool $pushMessage = false, string $messageLevel = MonologLoger::NOTICE);

}