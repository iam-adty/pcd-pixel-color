<?php

namespace IamAdty\PixelColor\Traits\StaticGenerator;

use IamAdty\PixelColor\Property\Defaults;

trait Component
{
    public static function create(...$properties): static
    {
        return new static(Defaults::from($properties));
    }

    public static function seed(...$seed): array
    {
        return Defaults::from($seed);
    }
}