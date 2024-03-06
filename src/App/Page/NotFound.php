<?php

namespace IamAdty\PixelColor\App\Page;

use IamAdty\PixelColor\App\Page;
use IamAdty\PixelColor\Component\Header;
use IamAdty\PixelColor\Component\Header\Size;

class NotFound extends Page
{
    protected function init(): void
    {
        parent::init();

        $this->getApp()->setResponseStatusCode(404);

        $this->add(Header::create(
            "404 Not Found",
            Size::large()
        ));
    }
}