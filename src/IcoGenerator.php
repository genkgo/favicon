<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class IcoGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly int $size = 48,
        private readonly ?string $backgroundColor = null,
    ) {
    }

    public function generate(): string
    {
        $imagick = new \Imagick();
        if ($this->backgroundColor) {
            $imagick->setBackgroundColor(new \ImagickPixel($this->backgroundColor));
        } else {
            $imagick->setBackgroundColor(new \ImagickPixel('transparent'));
        }

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
