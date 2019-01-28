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
     */
    public function sendMessageLog(array $record): bool
    {
        if (!isset($record['message']) || !$this->telegramBotToken || !$this->telegramChatIds) {
            return false;
        }
        $message = '<b>' . env('APP_NAME') . '</b>' . PHP_EOL
            . '<b>' . env('APP_ENV') . '</b>' . PHP_EOL
            . '<i>Message:</i>' . PHP_EOL
            . '<code>' . $record['message'] . '</code>';

        foreach ($this->telegramChatIds as $chatId) {

            $url = 'http://195.201.145.159/bot' . $this->telegramBotToken . '/sendMessage?'
                . http_build_query([
                    'text' => $message,
                    'chat_id' => $chatId,
                    'parse_mode' => 'html'
                ]);

            $ch = curl_init();
            $timeout = 3;

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
