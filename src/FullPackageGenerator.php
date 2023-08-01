<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class FullPackageGenerator implements PackageAppendInterface
{
    private function __construct(
        private readonly AggregatePackage $packages,
    ) {
    }

    public function package(Input $input, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        yield from $this->packages->package($input, $manifest, $rootPrefix);

        $impl = new \DOMImplementation();
        $doctype = $impl->createDocumentType('html');
        $document = $impl->createDocument(null, '', $doctype);

        $html = $document->createElement('html');
        $head = $document->createElement('head');

        foreach ($this->headTags($document, $manifest, $rootPrefix) as $tag) {
            $head->appendChild($tag);
        }

        $html->appendChild($head);
        $document->appendChild($html);
        $document->formatOutput = true;
        yield 'index.html' => $document->saveHTML();
    }

    public function headTags(\DOMDocument $document, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        yield from $this->packages->headTags($document, $manifest, $rootPrefix);

        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'theme-color');
        $meta->setAttribute('content', $manifest->themeColor);
        yield $meta;
    }

    public static function newGenerator(): self
    {
        return new self(
            new AggregatePackage([
                new ApplePackage(),
                new GenericIcoPackage(),
                new GenericPngPackage(),
                new MicrosoftTilePackage(),
            ]),
        );
    }
}
