<?php

namespace IamAdty\PixelColor\App\Page;

use IamAdty\PixelColor\App\Page;
use IamAdty\PixelColor\Component\Header;
use IamAdty\PixelColor\Component\Header\Size;

class MethodNotAllowed extends Page
{
    protected function init(): void
    {
        parent::init();

        $this->getApp()->setResponseStatusCode(405);

        $this->add(Header::create(
            "404 Method Not Allowed",
            Size::large()
        ));
    }
}