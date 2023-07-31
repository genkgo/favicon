<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class FullPackageGenerator implements PackageAppendInterface
{
    public function __construct(
        private readonly Input $input,
        private readonly string $themeColor,
        private readonly string $backgroundColor,
        private readonly string $tileColor,
        private readonly string $name,
        private readonly string $rootPrefix = '/',
        private readonly ?string $shortName = null,
    ) {
    }

    public function package(): \Generator
    {
        $generator = new AggregatePackage([
            new ApplePackage($this->input, $this->backgroundColor),
            new GenericIcoPackage($this->input, $this->backgroundColor),
            new GenericPngPackage(
                $this->input,
                $this->name,
                $this->shortName ?? $this->name,
                $this->themeColor,
                $this->rootPrefix,
                $this->tileColor,
                $this->backgroundColor,
            ),
            new MicrosoftTilePackage(
                $this->input,
                $this->tileColor,
                $this->rootPrefix,
                $this->backgroundColor,
            ),
        ]);
        return $generator->package();
    }
}
