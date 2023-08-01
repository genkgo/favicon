<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class ApplePackage implements PackageAppendInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly string $themeColor,
        private readonly string $rootPrefix = '/',
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

    public function headTags(\DOMDocument $document): \Generator
    {
        $rootPrefix = $this->rootPrefix;
        if (\substr($rootPrefix, -1, 1) === '/') {
            $rootPrefix = \substr($rootPrefix, 0, -1);
        }

        $link = $document->createElement('link');
        $link->setAttribute('rel', 'apple-touch-icon');
        $link->setAttribute('sizes', $this->size . 'x' . $this->size);
        $link->setAttribute('href', $rootPrefix . '/apple-touch-icon.png');
        yield $link;

        $link = $document->createElement('link');
        $link->setAttribute('rel', 'mask-icon');
        $link->setAttribute('href', $rootPrefix . '/safari-pinned-tab.svg');
        $link->setAttribute('color', $this->themeColor);
        yield $link;
    }
}
