<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class IcoGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly string $backgroundColor = 'transparent',
        private readonly int $size = 48,
    ) {
    }

    public function generate(): string
    {
        $imagick = new \Imagick();
        $imagick->setBackgroundColor(new \ImagickPixel($this->backgroundColor));
        $imagick->readImageFile($this->input->rewindedFileHandle());
        $imagick = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

        $imagick->scaleImage($this->size, $this->size);
        $imagick->setFormat('ico');
        $imagick->setImageFormat('ico');
        $imagick->setCompression(\Imagick::COMPRESSION_UNDEFINED);
        $imagick->setImageCompression(\Imagick::COMPRESSION_UNDEFINED);

        return $imagick->getImagesBlob();
    }
}
