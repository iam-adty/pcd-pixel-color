<?php

namespace IamAdty\PixelColor\Traits\StaticGenerator;

use IamAdty\PixelColor\Property\Defaults as PropertyDefaults;

trait Defaults
{
    public static function defaults(...$defaults): array
    {
        return PropertyDefaults::from($defaults);
    }
}