<?php

namespace IamAdty\PixelColor\App;

use IamAdty\PixelColor\Component\Property;

class Logger extends Property
{
    protected function name(): string
    {
        return 'logger';
    }

    /**
     * @param \Psr\Log\LoggerInterface $value
     * @return static
     */
    public static function value(...$value): static
    {
        return parent::value(...$value);
    }
}