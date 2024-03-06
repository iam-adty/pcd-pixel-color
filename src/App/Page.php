<?php

namespace IamAdty\PixelColor\App;

use Atk4\Ui\Layout;

class Page extends View
{
    public static function layout(): string|Layout
    {
        return Layout::class;
    }
}