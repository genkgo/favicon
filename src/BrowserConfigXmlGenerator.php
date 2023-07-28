<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class BrowserConfigXmlGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly string $tileColor,
        private readonly array $formats,
        private readonly string $rootPrefix = '/',
    ) {
    }

    public function generate(): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;
        $root = $document->createElement('browserconfig');
        $document->appendChild($root);

        $rootPrefix = $this->rootPrefix;
        if (\substr($rootPrefix, -1, 1) === '/') {
            $rootPrefix = \substr($rootPrefix, 0, -1);
        }

        $msApplication = $document->createElement('msapplication');
        $tile = $document->createElement('tile');
        foreach ($this->formats as $size => $file) {
            $tileType = $document->createElement($size . 'logo');
            $tileType->setAttribute('src', $rootPrefix . '/' . $file);
            $tile->appendChild($tileType);
        }

        $tileColor = $document->createElement('TileColor', $this->tileColor);
        $tile->appendChild($tileColor);
        $msApplication->appendChild($tile);

        $root->appendChild($msApplication);
        return $document->saveXML();
    }
}
