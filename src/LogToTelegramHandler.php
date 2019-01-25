<?php
/**
 * Created by PhpStorm.
 * User: sergei.nechaenko
 * Date: 25.01.2019
 * Time: 15:47
 */

namespace Nechaienko\TelegramLogging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class LogToDbHandler
 *
 * @package danielme85\LaravelLogToDB
 */
class LogToTelegramHandler extends AbstractProcessingHandler
{
    /**
     * LogToTelegramHandler constructor.
     * @param int $level
     * @param bool $bubble
     */
    function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        if (!empty($record)) {
            try {
                $telegramChat = new TelegramChat();
                $telegramChat->sendMessageLog($record);
            } catch (\Exception $e) {

            }
        }
    }
}
