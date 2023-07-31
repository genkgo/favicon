<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class FullPackageGenerator implements PackageAppendInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly string $backgroundColor,
        private readonly string $themeColor,
        private readonly string $name,
        private readonly string $rootPrefix = '/',
        private readonly ?string $shortName = null,
    ) {
    }

    public function package(): \Generator
    {
        $generator = new AggregatePackage([
            new GenericIcoPackage($this->input),
            new GenericPngPackage(
                $this->input,
                $this->name,
                $this->shortName ?? $this->name,
                $this->themeColor,
                $this->rootPrefix,
                $this->backgroundColor
            ),
            new AppleTouchIconPackage($this->input),
            new MicrosoftTilePackage($this->input, $this->backgroundColor, $this->rootPrefix),
        ]);
        return $generator->package();
    }
}
