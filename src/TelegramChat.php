<?php
/**
 * Created by PhpStorm.
 * User: sergei.nechaenko
 * Date: 25.01.2019
 * Time: 15:50
 */

namespace Nechaienko\TelegramLogging;

class TelegramChat
{
    protected $telegramChatIds;
    protected $telegramBotToken;

    function __construct()
    {
        $this->telegramBotToken = env('TELEGRAM_BOT_TOKEN', null);
        $this->telegramChatIds = config('telegram.telegram_admin_ids');
    }

    /**
     * @param array $record
     * @return bool
     * @throws \ReflectionException
     */
    public function sendMessageLog(array $record): bool
    {
        if (!isset($record['message']) || !$this->telegramBotToken || !$this->telegramChatIds) {
            return false;
        }

        if (isset($record['context']['exception'])) {
            $stacktrace = $record['context']['exception']->getTraceAsString() ?? '';
        } else {
            $stacktrace = '';
        }
        if (2000 < strlen($stacktrace)) {
            $stacktrace = substr($stacktrace, 0, 2000);
        }

        if (isset($record['context']['exception']) &&
            'PDOException' === (new \ReflectionClass($record['context']['exception']))->getShortName()
            && 2002 === $record['context']['exception']->getCode()) {
            $stacktrace = '-';
        }

        $message = '<i>Application Name:</i>' . PHP_EOL
            . '<b>' . env('APP_NAME') . '</b>' . PHP_EOL
            . '<i>Environment:</i>' . PHP_EOL
            . '<b>' . env('APP_ENV') . '</b>' . PHP_EOL
            . '<i>Message:</i>' . PHP_EOL
            . '<code>' . $record['message'] . '</code>' . PHP_EOL
            . '<i>StackTrace:</i>' . PHP_EOL
            . '<code>' . $stacktrace . '</code>';

        foreach ($this->telegramChatIds as $chatId) {

            $url = 'http://api.telegram.org/bot' . $this->telegramBotToken . '/sendMessage?'
                . http_build_query([
                    'text' => $message,
                    'chat_id' => $chatId,
                    'parse_mode' => 'html'
                ]);

            $ch = curl_init();
            $timeout = 3;

            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


            curl_exec($ch);
            curl_close($ch);
        }

        return true;
    }
}
