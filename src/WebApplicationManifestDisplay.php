<?php


declare(strict_types=1);

namespace Genkgo\Favicon;

enum WebApplicationManifestDisplay: string
{
    case Fullscreen = 'fullscreen';
    case Standalone = 'standalone';
    case MinimalUI = 'minimal-ui';
    case Browser = 'browser';
}
