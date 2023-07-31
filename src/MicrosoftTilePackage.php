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
        private readonly string $backgroundColor = 'transparent',
        private readonly array $sizes = [70, 150, 310],
    ) {
    }

    public function package(): \Generator
    {
        foreach ($this->sizes as $size) {
            $generator = new PngGenerator($this->input, $size, $this->backgroundColor);
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
}
