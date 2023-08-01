<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class GenericIcoPackage implements PackageAppendInterface
{
    /**
     * @param array<int, int> $sizes
     */
    public function __construct(
        private readonly Input $input,
        private readonly array $sizes = [48],
    ) {
    }

    public function package(): \Generator
    {
        $first = true;
        foreach ($this->sizes as $size) {
            $generator = new IcoGenerator($this->input, $size);
            $blob = $generator->generate();
            if ($first) {
                yield 'favicon.ico' => $blob;
            }

            $first = false;
            yield 'favicon-' . $size . 'x' . $size . '.ico' => $blob;
        }
    }
}
