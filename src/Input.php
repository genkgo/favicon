<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class Input
{
    private function __construct(
        private readonly string $stream,
        private readonly \Imagick $image,
        public readonly InputImageType $type,
        public readonly string $backgroundColor = 'transparent',
    )
    {
    }

    /**
     * @return resource
     */
    public function newResourceHandle()
    {
        return \fopen($this->stream, 'r');
    }

    public function newImagick(): \Imagick
    {
        return clone $this->image;
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickPixelException
     * @throws \ImagickDrawException
     */
    public static function fromFile(
        string $fileName,
        InputImageType $type,
        string $backgroundColor = 'transparent',
    ): self
    {
        if (!\is_file($fileName)) {
            throw new \InvalidArgumentException('File ' . $fileName . ' does not exist');
        }

        return new self($fileName, self::createFileBaseImagick($fileName, $backgroundColor), $type, $backgroundColor);
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickPixelException
     * @throws \ImagickDrawException
     */
    private static function createFileBaseImagick(string $fileName, string $backgroundColor): \Imagick
    {
        $imagick = new \Imagick();
        $imagick->setBackgroundColor(new \ImagickPixel($backgroundColor));
        $imagick->readImageFile(\fopen($fileName, 'r'));
        $imagick->trimImage(0);

        $width = $imagick->getImageWidth();
        $height = $imagick->getImageHeight();

        $resizeTo = \max($width, $height);
        $padding = (int)(0.05 * $resizeTo);

        $composite = new \Imagick();
        $composite->newImage($resizeTo, $resizeTo, new \ImagickPixel('none'));

        $paddingDraw = new \ImagickDraw();
        $paddingDraw->setFillColor($imagick->getImageBackgroundColor());
        $paddingDraw->roundRectangle(
            0,
            0,
            $resizeTo,
            $resizeTo,
            $padding,
            $padding
        );

        $composite->drawImage($paddingDraw);
        $composite->setImageFormat('png');
        $composite->compositeImage(
            $imagick,
            \Imagick::COMPOSITE_DEFAULT,
            (int)(($resizeTo - $width) / 2),
            (int)(($resizeTo - $height) / 2),
        );

        return $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickPixelException
     * @throws \ImagickDrawException
     */
    public static function digit(
        string $digit,
        string $color,
        string $iconBackground,
        string $backgroundColor = 'transparent'
    ): self
    {
        $imagick = self::createDigitImagick($digit, $color, $iconBackground, $backgroundColor);
        return new self(
            'data://image/png;base64,' . \base64_encode((string)$imagick),
            $imagick,
            InputImageType::PNG,
            $backgroundColor
        );
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickPixelException
     * @throws \ImagickDrawException
     */
    private static function createDigitImagick(
        string $digit,
        string $color,
        string $iconBackground,
        string $backgroundColor
    ): \Imagick
    {
        $size = 512;

        $imagick = new \Imagick();
        $imagick->newImage($size, $size, new \ImagickPixel($backgroundColor));
        $imagick->setFormat('png');
        $imagick->setImageFormat('png');

        $circle = new \ImagickDraw();
        $circle->setFillColor(new \ImagickPixel($iconBackground));
        $circle->roundRectangle(
            0,
            0,
            $size,
            $size,
            $size,
            $size
        );
        $imagick->drawImage($circle);

        $draw = new \ImagickDraw();
        $draw->setFillColor(new \ImagickPixel($color));
        $draw->setFontSize((int)($size / 2));

        $metrics = $imagick->queryFontMetrics($draw, $digit);
        $letterWidth = $metrics['boundingBox']['x2'] + ($metrics['boundingBox']['x1'] * -1);
        $letterHeight = $metrics['boundingBox']['y2'] + ($metrics['boundingBox']['y1'] * -1);

        $imagick->annotateImage(
            $draw,
            $metrics['boundingBox']['x1'] * -1 + $size / 2 - $letterWidth / 2,
            $metrics['boundingBox']['y2'] + $size / 2 - $letterHeight / 2,
            0,
            $digit
        );

        return $imagick;
    }
}
