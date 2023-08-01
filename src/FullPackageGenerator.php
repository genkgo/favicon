<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class FullPackageGenerator implements PackageAppendInterface
{
    private function __construct(
        private readonly AggregatePackage $packages,
        private readonly string $title,
        private readonly string $themeColor
    ) {
    }

    public function package(): \Generator
    {
        yield from $this->packages->package();

        $impl = new \DOMImplementation();
        $doctype = $impl->createDocumentType('html');
        $document = $impl->createDocument(null, '', $doctype);

        $html = $document->createElement('html');
        $head = $document->createElement('head');

        $meta = $document->createElement('meta');
        $meta->setAttribute('http-equiv', 'Content-Type');
        $meta->setAttribute('content', 'text/html; charset=utf-8');
        $head->appendChild($meta);

        $title = $document->createElement('title', $this->title);
        $head->appendChild($title);

        foreach ($this->headTags($document) as $tag) {
            $head->appendChild($tag);
        }

        $html->appendChild($head);
        $document->appendChild($html);
        $document->formatOutput = true;
        yield 'index.html' => $document->saveHTML();
    }

    public function headTags(\DOMDocument $document): \Generator
    {
        yield from $this->packages->headTags($document);

        $meta = $document->createElement('meta');
        $meta->setAttribute('name', 'theme-color');
        $meta->setAttribute('content', $this->themeColor);
        yield $meta;
    }

    public static function newGenerator(
        Input $input,
        string $themeColor,
        string $tileColor,
        string $name,
        string $rootPrefix = '/',
        ?string $shortName = null,
    ): self
    {
        return new self(
            new AggregatePackage([
                new ApplePackage($input, $themeColor, $rootPrefix),
                new GenericIcoPackage($input, $rootPrefix),
                new GenericPngPackage(
                    $input,
                    $name,
                    $shortName ?? $name,
                    $themeColor,
                    $rootPrefix,
                    $tileColor,
                ),
                new MicrosoftTilePackage(
                    $input,
                    $tileColor,
                    $rootPrefix,
                ),
            ]),
            $name,
            $themeColor
        );
    }
}
