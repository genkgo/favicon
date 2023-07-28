<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class FullPackageGenerator implements PackageAppendInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly string $tileColor,
        private readonly string $rootPrefix = '/',
    ) {
    }

    public function package(): \Generator
    {
        $generator = new AggregatePackage([
            new GenericIcoPackage($this->input),
            new GenericPngPackage($this->input),
            new AppleTouchIconPackage($this->input),
            new AndroidChromePackage($this->input),
            new MicrosoftTilePackage($this->input, $this->tileColor, $this->rootPrefix),
        ]);
        return $generator->package();
    }
}
