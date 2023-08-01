<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class ApplePackage implements PackageAppendInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly int $size = 180,
    ) {
    }

    public function package(): \Generator
    {
        $generator = new AppleTouchGenerator($this->input, $this->size);
        yield 'apple-touch-icon.png' => $generator->generate();

        $generator = AppleSafariPinGenerator::cliDetectImageMagickVersion($this->input);
        yield 'safari-pinned-tab.svg' => $generator->generate();
    }
}
