<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class GenericIcoPackage implements PackageAppendInterface
{
    /**
     * @param array<int, int> $sizes
     */
    public function __construct(
        private readonly array $sizes = [48],
    ) {
    }

    public function package(Input $input, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        $first = true;
        foreach ($this->sizes as $size) {
            $generator = new IcoGenerator($input, $size);
            $blob = $generator->generate();
            if ($first) {
                yield 'favicon.ico' => $blob;
            }

            $first = false;
            yield 'favicon-' . $size . 'x' . $size . '.ico' => $blob;
        }
    }

    public function headTags(\DOMDocument $document, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        if (\substr($rootPrefix, -1, 1) === '/') {
            $rootPrefix = \substr($rootPrefix, 0, -1);
        }

        foreach ($this->sizes as $size) {
            $link = $document->createElement('link');
            $link->setAttribute('rel', 'shortcut icon');
            $link->setAttribute('href', $rootPrefix . '/favicon-' . $size . 'x' . $size . '.ico');
            yield $link;
        }
    }
}
