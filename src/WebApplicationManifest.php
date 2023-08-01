<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class WebApplicationManifest
{
    public function __construct(
        public readonly WebApplicationManifestDisplay $display,
        public readonly string $name,
        public readonly string $shortName,
        public readonly string $themeColor,
        public readonly string $backgroundColor,
    )
    {
    }
}
