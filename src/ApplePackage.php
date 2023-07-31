<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class ApplePackage implements PackageAppendInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly string $backgroundColor = 'transparent',
        private readonly int $size = 180,
    ) {
    }

    public function package(): \Generator
    {
        \var_dump($this->backgroundColor);
        $generator = new AppleTouchGenerator($this->input, $this->size, $this->backgroundColor);
        yield 'apple-touch-icon.png' => $generator->generate();

        $generator = AppleSafariPinGenerator::cliDetectImageMagickVersion($this->input);
        yield 'safari-pinned-tab.svg' => $generator->generate();
    }
}
