## About Laravel-telegram-logging

This package gives opportunity **logging to telegram** with custom
***[Laravel logging](https://laravel.com/docs/5.7/logging)***

Installation
-----------------------------------
```
composer require nechaienko/laravel-telegram-logging
```
Configuration
-----------------------------------
Add configurations to file `..\your_project\config\logging.php`
```
'telegram' => [
            'driver' => 'custom',
            'via' => \Nechaienko\TelegramLogging\LogToTelegram::class,
            'level' => 'info'
        ],
``` 

Add **Telegram Bot Token** to file `..\your_project\.env`
```
TELEGRAM_BOT_TOKEN=****
```

Create file **telegram.php** in config folder
`..\your_project\config\telegram.php'`

```
<?php

return [
    'telegram_admin_ids' => [
        '*********', //developer 1
        '*********', //developer 2
    ]
];
```

Usage
-----------------------------------

```
use Illuminate\Support\Facades\Log;
...
Log::channel('telegram')->info('message');
``` 