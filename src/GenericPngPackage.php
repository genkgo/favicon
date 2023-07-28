<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class GenericPngPackage implements PackageAppendInterface
{
    /**
     * @param array<int, int> $sizes
     */
    public function __construct(
        private readonly Input $input,
        private readonly array $sizes = [32, 16, 48, 57, 76, 96, 128, 192, 228],
        private readonly ?string $backgroundColor = null,
    ) {
    }

    public function package(): \Generator
    {
        $first = true;
        foreach ($this->sizes as $size) {
            $generator = new PngGenerator($this->input, $size, $this->backgroundColor);
            $blob = $generator->generate();
            if ($first) {
                yield 'favicon.png' => $blob;
            }

            $first = false;
            yield 'favicon-' . $size . 'x' . $size . '.png' => $blob;
        }
    }
}
