<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class ApplePackage implements PackageAppendInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly int $size = 180,
        private readonly ?string $backgroundColor = null,
    ) {
    }

    public function package(): \Generator
    {
        $generator = new PngGenerator($this->input, $this->size, $this->backgroundColor);
        yield 'apple-touch-icon.png' => $generator->generate();

        $generator = SafariPinGenerator::cliDetectImageMagickVersion($this->input);
        yield 'safari-pinned-tab.svg' => $generator->generate();
    }
}
