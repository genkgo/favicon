<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class WebApplicationManifestJsonGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly WebApplicationManifest $manifest,
        private readonly array $pngFormats,
    ) {
    }

    public function generate(): string
    {
        $manifest = [
            'display' => $this->manifest->display->value,
            'name' => $this->manifest->name,
            'short_name' => $this->manifest->shortName,
            'theme_color' => $this->manifest->themeColor,
            'background_color' => $this->manifest->backgroundColor,
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
