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
            . '<i>Error:</i>' . PHP_EOL
            . '<code>' . $record['message'] . '</code>';

        foreach ($this->telegramChatIds as $chatId) {
            $a = file_get_contents(
                'https://api.telegram.org/bot' . $this->telegramBotToken . '/sendMessage?'
                . http_build_query([
                    'text' => $message,
                    'chat_id' => $chatId,
                    'parse_mode' => 'html'
                ])
            );
        }

        return true;
    }
}
