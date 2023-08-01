<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

interface PackageAppendInterface
{
    /**
     * @return \Generator<string, string>
     * @throws \ImagickException
     */
    public function package(): \Generator;

    /**
     * @param \DOMDocument $document
     * @return \Generator<int|string, \DOMElement>
     */
    public function headTags(\DOMDocument $document): \Generator;
}
