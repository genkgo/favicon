<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class MicrosoftTilePackage implements PackageAppendInterface
{
    /**
     * @param array<int, int> $sizes
     */
    public function __construct(
        private readonly array $sizes = [150, 310],
    ) {
    }

    public function package(Input $input, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        foreach ($this->sizes as $size) {
            $generator = new PngGenerator($input, $size);
            yield 'mstile-' . $size . 'x' . $size . '.png' => $generator->generate();
        }

        $configSizes = [];
        foreach ($this->sizes as $size) {
            $configSizes['square' . $size . 'x' . $size] = 'mstile-' . $size . 'x' . $size . '.png';
        }

        $browserConfigXml = new BrowserConfigXmlGenerator(
            $manifest->backgroundColor,
            $configSizes,
            $rootPrefix,
        );

        yield 'browserconfig.xml' => $browserConfigXml->generate();
    }

    public function headTags(\DOMDocument $document, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        if (\substr($rootPrefix, -1, 1) === '/') {
            $rootPrefix = \substr($rootPrefix, 0, -1);
        }

        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'msapplication-config');
        $meta->setAttribute('content', $rootPrefix . '/browserconfig.xml');
        yield $meta;

        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'msapplication-TileColor');
        $meta->setAttribute('content', $manifest->backgroundColor);
        yield $meta;

        $defaultSize = $this->sizes[\array_key_first($this->sizes)];
        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'msapplication-TileImage');
        $meta->setAttribute('content', $rootPrefix . '/mstile-' . $defaultSize . 'x' . $defaultSize . '.png');
        yield $meta;
    }
}
