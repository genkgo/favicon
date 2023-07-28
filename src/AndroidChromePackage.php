<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class AndroidChromePackage implements PackageAppendInterface
{
    /**
     * @param array<int, int> $sizes
     */
    public function __construct(
        private readonly Input $input,
        private readonly array $sizes = [192, 512],
        private readonly ?string $backgroundColor = null,
    ) {
    }

    public function package(): \Generator
    {
        foreach ($this->sizes as $size) {
            $generator = new PngGenerator($this->input, $size, $this->backgroundColor);
            yield 'android-chrome-' . $size . 'x' . $size . '.png' => $generator->generate();
        }
    }
}
