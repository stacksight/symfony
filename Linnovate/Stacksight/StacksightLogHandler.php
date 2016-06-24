<?php

namespace Linnovate\Stacksight;

use Linnovate\Stacksight\Stacksight;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Psr\Log\LogLevel;

class StacksightLogHandler extends AbstractProcessingHandler
{

    private $ss_client;

    public function __construct(Stacksight $stacksight, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->ss_client = $stacksight->getClient();
    }

    protected function write(array $record)
    {
        if($error_code_txg = self::codeToString(strtolower($record['level_name']))){
            print_r('LOG EVENT <br>');
            $data = $this->generateDataStream($record);
            $this->ss_client->sendLog($data, strtolower($record['level_name']));
        }
    }

    private static function codeToString($code)
    {
        switch ($code){
            case LogLevel::EMERGENCY:
                return 'E_EMERGENCY';
            case LogLevel::ALERT:
                return 'E_PARSE';
            case LogLevel::CRITICAL:
                return 'E_ERROR';
            case LogLevel::ERROR:
                return 'E_ERROR';
            case LogLevel::WARNING:
                return 'E_WARNING';
            case LogLevel::NOTICE:
                return 'E_NOTICE';
            case LogLevel::INFO:
                return 'E_INFO';
            case LogLevel::DEBUG:
                return 'DEBUG';
        }
        return false;
    }

    protected function generateDataStream($record)
    {
        return (string) $record['formatted'];
    }
}