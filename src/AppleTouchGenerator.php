<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class AppleTouchGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly int $size,
    ) {
    }

    public function generate(): string
    {
        $imagick = $this->input->newImagick();

        $newWidth = $imagick->getImageWidth();
        $newHeight = $imagick->getImageHeight();

        $padding = (int)(0.05 * $newWidth);
        $radius = (int)(0.15 * $newWidth);
        $composite = new \Imagick();
        $composite->newImage($newWidth + 2 * $padding, $newHeight + 2 * $padding, new \ImagickPixel('none'));

        $paddingDraw = new \ImagickDraw();
        $paddingDraw->setFillColor(new \ImagickPixel($this->input->backgroundColor));
        $paddingDraw->roundRectangle(
            0,
            0,
            $newWidth + 2 * $padding,
            $newHeight + 2 * $padding,
            $radius,
            $radius
        );
        $composite->drawImage($paddingDraw);
        $composite->setImageFormat('png');
        $composite->compositeImage(
            $imagick,
            \Imagick::COMPOSITE_DEFAULT,
            $padding,
            $padding,
        );

        $composite->scaleImage($this->size, $this->size);
        $composite->setFormat('png');
        $composite->setImageFormat('png');
        $composite->setCompression(\Imagick::COMPRESSION_ZIP);
        $composite->setImageCompression(\Imagick::COMPRESSION_ZIP);

        return $composite->getImagesBlob();
    }
}
