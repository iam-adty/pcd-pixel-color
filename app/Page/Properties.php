<?php

namespace App;

use IamAdty\PixelColor\App\Logger;
use IamAdty\PixelColor\App\TemplateDir;
use IamAdty\PixelColor\App\Title;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;

class Properties
{
    public static function values(): array
    {
        return [
            Title::value('Pixel Color'),
            TemplateDir::value([
                __DIR__ . '/../template'
            ]),
            Logger::value(new MonologLogger(
                'app',
                [
                    new StreamHandler(
                        __DIR__ . '/../log/app.log',
                        Level::Debug
                    )
                ]
            )),
        ];
    }
}
