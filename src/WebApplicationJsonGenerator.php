<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class WebApplicationJsonGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly WebApplicationManifestDisplay $display,
        private readonly string $name,
        private readonly string $shortName,
        private readonly string $themeColor,
        private readonly string $backgroundColor,
        private readonly array $pngFormats,
    ) {
    }

    public function generate(): string
    {
        $manifest = [
            'display' => $this->display->value,
            'name' => $this->name,
            'short_name' => $this->shortName,
            'theme_color' => $this->themeColor,
            'background_color' => $this->backgroundColor,
            'icons' => [],
        ];

        foreach ($this->pngFormats as $size => $src) {
            $manifest['icons'][] = [
                'src' => $src,
                'sizes' => $size,
                'type' => 'image/png'
            ];
        }

        return \json_encode($manifest, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES);
    }
}
