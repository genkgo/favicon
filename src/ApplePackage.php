<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class ApplePackage implements PackageAppendInterface
{
    public function __construct(
        private readonly int $size = 180,
    ) {
    }

    public function package(Input $input, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        $generator = new AppleTouchGenerator($input, $this->size);
        yield 'apple-touch-icon.png' => $generator->generate();

        $generator = AppleSafariPinGenerator::cliDetectImageMagickVersion($input);
        yield 'safari-pinned-tab.svg' => $generator->generate();
    }

    public function headTags(\DOMDocument $document, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
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
        $link->setAttribute('color', $manifest->themeColor);
        yield $link;
    }
}
