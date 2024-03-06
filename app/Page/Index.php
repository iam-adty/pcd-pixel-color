<?php

namespace App\Page;

use Atk4\Ui\Accordion;
use Atk4\Ui\Columns;
use Atk4\Ui\Form;
use Atk4\Ui\Form\Control\UploadImage;
use Atk4\Ui\Layout;
use Atk4\Ui\Table\Column\Link;
use Atk4\Ui\Image;
use Atk4\Ui\Js\JsBlock;
use Atk4\Ui\VirtualPage;
use IamAdty\PixelColor\App\Page;
use IamAdty\PixelColor\Component\Table;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Index extends Page
{
    public static function layout(): string|Layout
    {
        return Layout::class;
    }

    protected function build()
    {
        $colLayout = Columns::addTo($this);

        $data = array_map(function($image) {
            return [
                'image' => $image
            ];
        }, scandir(__DIR__ . '/../../data/images'));

        unset($data[0]);
        unset($data[1]);

        $table = Table::addTo($colLayout->addColumn(2));
        $table->setSource($data);

        $table->addDecorator('image', new Link('/?image={$image}'));

        $colKanan = Columns::addTo($colLayout->addColumn(14));

        $formUploadImage = Form::addTo($colKanan->addRow());

        $uploadImageControl = $formUploadImage->addControl('image', [
            UploadImage::class,
            [
                'placeholder' => 'Click here to choose image'
            ]
        ]);

        if ($uploadImageControl instanceof UploadImage) {
            $uploadImageControl->onUpload(function ($image) use ($formUploadImage) {
                if ($image['error'] !== 0) {
                    return $formUploadImage->jsError('image', 'Error upload image');
                } else {
                    if (!move_uploaded_file($image['tmp_name'], __DIR__ . '/../../data/images/' . $image['name'])) {
                        return $formUploadImage->jsError('image', 'Error upload image');
                    } else {
                        return $formUploadImage->getApp()->jsRedirect('/');
                    }
                }
            });
        }

        $image = $this->getApp()->tryGetRequestQueryParam('image');

        if (!is_null($image)) {
            $this->stickyGet('image');

            $manager = new ImageManager(new Driver());

            $readImage = $manager->read(__DIR__ . '/../../data/images/' . $image);

            $metadata = [
                [
                    'info' => 'name',
                    'value' => $image
                ],
                [
                    'info' => 'width',
                    'value' => $readImage->width()
                ],
                [
                    'info' => 'height',
                    'value' => $readImage->height()
                ]
            ];

            $tableMetadataImage = Table::addTo($colKanan->addRow());
            $tableMetadataImage->setSource($metadata);

            $imageData = file_get_contents(__DIR__ . '/../../data/images/' . $image);
            $imageType = pathinfo(__DIR__ . '/../../data/images/' . $image, PATHINFO_EXTENSION);

            Image::addTo($colKanan->addRow(), [
                'data:image/' . $imageType . ';base64,' . base64_encode($imageData)
            ]);

            $formGetPixel = Form::addTo($colKanan->addRow());
            $pixelY = $formGetPixel->addControl('pixel_y', [
                'caption' => 'pixel Y',
            ]);
            
            $pixelX = $formGetPixel->addControl('pixel_x', [
                'caption' => 'pixel X'
            ]);

            $rgbColorControl = $formGetPixel->addControl('rgb');
            $hexColorControl = $formGetPixel->addControl('hex');

            $rgbColorArg = $this->getApp()->tryGetRequestQueryParam('rgb_color');
            $hexColorArg = $this->getApp()->tryGetRequestQueryParam('hex_color');

            if (!is_null($rgbColorArg)) {
                $rgbColorControl->set($rgbColorArg);
            }

            if (!is_null($hexColorArg)) {
                $hexColorControl->set($hexColorArg);
            }

            $formGetPixel->buttonSave->set('Get Pixel');

            $formGetPixel->onSubmit(function (Form $form) use ($readImage) {
                $pickColor = $readImage->pickColor(
                    $this->getApp()->getRequestPostParam('pixel_x'),
                    $this->getApp()->getRequestPostParam('pixel_y')
                );

                return new JsBlock([
                    $form->jsReload([
                        'hex_color' => $pickColor->toHex(),
                        'rgb_color' => $pickColor->toString(),
                        'pixel_x' => $this->getApp()->getRequestPostParam('pixel_x'),
                        'pixel_y' => $this->getApp()->getRequestPostParam('pixel_y'),
                    ])
                ]);
            });

            $hexPixelData = [];
            $rgbPixelData = [];
    
            for ($h = 0; $h < $readImage->height(); $h++) {
                    $hexColor = [];
                    $rgbColor = [];
                for ($w = 0; $w < $readImage->width(); $w++) {
                    $hexColor[] = '[' . $h . 'x' . $w . '=' . $readImage->pickColor($w, $h)->toHex() . ']';
                    $rgbColor[] = '[' . $h . 'x' . $w . '=' . $readImage->pickColor($w, $h)->toString() . ']';
                }

                $hexPixelData[] = [
                    'color' => implode(', ', $hexColor)
                ];

                $rgbPixelData[] = [
                    'color' => implode(', ', $rgbColor)
                ];
            }

            $accordion = Accordion::addTo($colKanan->addRow());
            $accordion->addSection('Hex Color', function (VirtualPage $virtualPage) use ($hexPixelData) {
                $tableColor = Table::addTo($virtualPage);
                $tableColor->setSource($hexPixelData);

                return $virtualPage;
            });

            $accordion->addSection('RGB Color', function (VirtualPage $virtualPage) use ($rgbPixelData) {
                $tableColor = Table::addTo($virtualPage);
                $tableColor->setSource($rgbPixelData);

                return $virtualPage;
            });
        }
    }
}