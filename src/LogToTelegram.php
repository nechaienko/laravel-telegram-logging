<?php
/**
 * Created by PhpStorm.
 * User: sergei.nechaenko
 * Date: 25.01.2019
 * Time: 15:42
 */

namespace Nechaienko\TelegramLogging;

use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class LogToTelegram
{
    protected const LEVEL_INDEX = 'level';
    protected const NAME_INDEX = 'name';

    protected const DEFAULT_CONFIG = [
        self::LEVEL_INDEX => 'info',
        self::NAME_INDEX => 'telegramLogger'
    ];

    /**
     * Create a custom Monolog instance.
     *
     * @param  array $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $resultConfig = $this->prepareConfig($config);
        $handler = new LogToTelegramHandler(
            $resultConfig[self::LEVEL_INDEX]
        );

        $processor = new IntrospectionProcessor();

        $logger = new Logger(
            $resultConfig[self::NAME_INDEX],
            [
                $handler
            ],
            [
                $processor
            ]
        );

        return $logger;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function prepareConfig(array $config): array
    {
        $resultConfig = [];
        foreach (self::DEFAULT_CONFIG as $configKey => $configValue) {
            if (array_key_exists($configKey, $config)) {
                $resultConfig [$configKey] = $config[$configKey];
            } else {
                $resultConfig [$configKey] = $configValue;
            }
        }
        return $resultConfig;
    }
}
