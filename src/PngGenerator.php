<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class PngGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly int $size,
        private readonly string $backgroundColor = 'transparent',
    ) {
    }

    public function generate(): string
    {
        $imagick = new \Imagick();
        $imagick->setBackgroundColor(new \ImagickPixel($this->backgroundColor));
        $imagick->readImageFile($this->input->rewindedFileHandle());
        $imagick = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

        $imagick->scaleImage($this->size, $this->size);
        $imagick->setFormat('png');
        $imagick->setImageFormat('png');
        $imagick->setCompression(\Imagick::COMPRESSION_ZIP);
        $imagick->setImageCompression(\Imagick::COMPRESSION_ZIP);

        return $imagick->getImagesBlob();
    }
}
