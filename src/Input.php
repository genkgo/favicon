<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class Input
{
    /**
     * @var resource
     */
    private $file;

    /**
     * @param resource $file
     */
    private function __construct(
        $file,
        public readonly InputImageType $type,
        public readonly string $backgroundColor = 'transparent',
    ) {
        $this->file = $file;
    }

    /**
     * @return resource
     */
    public function rewindedFileHandle()
    {
        \rewind($this->file);
        return $this->file;
    }

    public static function fromFile(
        string $fileName,
        InputImageType $type,
        string $backgroundColor = 'transparent',
    ): self
    {
        if (!\is_file($fileName)) {
            throw new \InvalidArgumentException('File ' . $fileName . ' does not exist');
        }

        return new self(fopen($fileName, 'r'), $type, $backgroundColor);
    }

    public static function fromString(
        string $content,
        InputImageType $type,
        string $backgroundColor = 'transparent',
    ): self
    {
        $resource = \fopen('php://memory', 'r+');
        \fwrite($resource, $content);
        \rewind($resource);
        return new self($resource, $type, $backgroundColor);
    }

    public static function letter(string $letter, string $color, string $backgroundColor): self
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0, 0, 100, 100">
    <circle cx="50" cy="50" r="49" fill="%s"></circle>
    <text x="49" y="71.5" font-family="sans-serif" font-size="60" font-weight="700" text-anchor="middle" fill="%s">%s</text>
</svg>';
        return self::fromString(
            '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . \sprintf($svg, $backgroundColor, $color, $letter),
            InputImageType::SVG
        );
    }
}
