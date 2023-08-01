<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class GenericPngPackage implements PackageAppendInterface
{
    /**
     * @param array<int, int> $sizes
     */
    public function __construct(
        private readonly array $sizes = [32, 16, 48, 57, 76, 96, 128, 192, 228, 512],
    ) {
    }

    public function package(Input $input, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        if (\substr($rootPrefix, -1, 1) === '/') {
            $rootPrefix = \substr($rootPrefix, 0, -1);
        }

        $first = true;
        $manifestFormats = [];
        foreach ($this->sizes as $size) {
            $generator = new PngGenerator($input, $size);
            $blob = $generator->generate();
            if ($first) {
                yield 'favicon.png' => $blob;
            }

            $first = false;
            yield 'favicon-' . $size . 'x' . $size . '.png' => $blob;
            $manifestFormats[$size . 'x' . $size] = $rootPrefix . '/favicon-' . $size . 'x' . $size . '.png';
        }

        $manifestGenerator = new WebApplicationManifestJsonGenerator($manifest, $manifestFormats);
        yield 'web-app-manifest.json' => $manifestGenerator->generate();
    }

    public function headTags(\DOMDocument $document, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        if (\substr($rootPrefix, -1, 1) === '/') {
            $rootPrefix = \substr($rootPrefix, 0, -1);
        }

        foreach ($this->sizes as $size) {
            $link = $document->createElement('link');
            $link->setAttribute('rel', 'icon');
            $link->setAttribute('type', 'image/png');
            $link->setAttribute('href', $rootPrefix . '/favicon-' . $size . 'x' . $size . '.png');
            $link->setAttribute('sizes', $size . 'x' . $size);
            yield $link;
        }

        $link = $document->createElement('link');
        $link->setAttribute('rel', 'manifest');
        $link->setAttribute('href', $rootPrefix . '/web-app-manifest.json');
        yield $link;
    }
}
