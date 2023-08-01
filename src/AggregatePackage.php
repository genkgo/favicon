<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class AggregatePackage implements PackageAppendInterface
{
    /**
     * @param iterable<int|string, PackageAppendInterface> $generators
     */
    public function __construct(private readonly iterable $generators)
    {
    }

    /**
     * @throws \ImagickException
     */
    public function package(Input $input, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        foreach ($this->generators as $generator) {
            yield from $generator->package($input, $manifest, $rootPrefix);
        }
    }

    public function headTags(\DOMDocument $document, WebApplicationManifest $manifest, string $rootPrefix): \Generator
    {
        foreach ($this->generators as $generator) {
            yield from $generator->headTags($document, $manifest, $rootPrefix);
        }
    }
}
