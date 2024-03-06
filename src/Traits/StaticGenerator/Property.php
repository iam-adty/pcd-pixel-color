<?php

namespace IamAdty\PixelColor\Traits\StaticGenerator;

use IamAdty\PixelColor\Property\Defaults;

trait Property
{
    public static function property(...$properties): array
    {
        return Defaults::from($properties);
    }
}