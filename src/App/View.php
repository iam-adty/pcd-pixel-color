<?php

namespace IamAdty\PixelColor\App;

use Atk4\Ui\View as UiView;
use IamAdty\PixelColor\Traits\StaticGenerator\Component;
use IamAdty\PixelColor\Traits\StaticGenerator\Defaults;
use IamAdty\PixelColor\Traits\StaticGenerator\Property;

/**
 * @method \IamAdty\PixelColor\App getApp()
 */
class View extends UiView
{
    use Component;
    use Property;
    use Defaults;

    protected function init(): void
    {
        parent::init();

        $this->build();
    }

    protected function build()
    {}
}