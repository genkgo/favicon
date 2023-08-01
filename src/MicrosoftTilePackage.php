<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class MicrosoftTilePackage implements PackageAppendInterface
{
    /**
     * @param array<int, int> $sizes
     */
    public function __construct(
        private readonly Input $input,
        private readonly string $tileColor,
        private readonly string $rootPrefix = '/',
        private readonly array $sizes = [150, 310],
    ) {
    }

    public function package(): \Generator
    {
        foreach ($this->sizes as $size) {
            $generator = new PngGenerator($this->input, $size);
            yield 'mstile-' . $size . 'x' . $size . '.png' => $generator->generate();
        }

        $configSizes = [];
        foreach ($this->sizes as $size) {
            $configSizes['square' . $size . 'x' . $size] = 'mstile-' . $size . 'x' . $size . '.png';
        }

        $browserConfigXml = new BrowserConfigXmlGenerator(
            $this->tileColor,
            $configSizes,
            $this->rootPrefix,
        );

        yield 'browserconfig.xml' => $browserConfigXml->generate();
    }

    public function headTags(\DOMDocument $document): \Generator
    {
        $rootPrefix = $this->rootPrefix;
        if (\substr($rootPrefix, -1, 1) === '/') {
            $rootPrefix = \substr($rootPrefix, 0, -1);
        }

        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'msapplication-config');
        $meta->setAttribute('content', $rootPrefix . '/browserconfig.xml');
        yield $meta;

        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'msapplication-TileColor');
        $meta->setAttribute('content', $this->tileColor);
        yield $meta;

        $defaultSize = $this->sizes[\array_key_first($this->sizes)];
        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'msapplication-TileImage');
        $meta->setAttribute('content', 'mstile-' . $defaultSize . 'x' . $defaultSize . '.png');
        yield $meta;
    }
}
