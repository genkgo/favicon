<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

final class AppleSafariPinGenerator implements GeneratorInterface
{
    public function __construct(private readonly Input $input, private readonly string $executable = 'magick')
    {
    }

    public function generate(): string
    {
        if ($this->input->type === InputImageType::SVG) {
            return \stream_get_contents($this->input->newResourceHandle());
        }

        // If someone knows how to convert a PNG to SVG by using the Imagick extension in PHP, I'd love to know how.
        // https://github.com/Imagick/imagick/issues/622
        return $this->tempFile(
            function ($source, $target) {
                $sourceHandle = \fopen($source, 'r+');
                \stream_copy_to_stream($this->input->newResourceHandle(), $sourceHandle);
                \fclose($sourceHandle);

                $descriptor = [
                    0 => ["pipe", "r"],
                    1 => ["pipe", "w"],
                    2 => ["pipe", "w"],
                ];

                $process = \proc_open([$this->executable, $source, 'SVG:' . $target], $descriptor, $pipes, '/tmp');

                $stdout = \stream_get_contents($pipes[1]);
                $stderr = \stream_get_contents($pipes[2]);

                \fclose($pipes[0]);
                \fclose($pipes[1]);
                \fclose($pipes[2]);

                $return = proc_close($process);
                if ($return !== 0) {
                    throw new \UnexpectedValueException(
                        'Failed to convert PNG to SVG. Got return code ' . $return . '.'  . $stdout . $stderr
                    );
                }

                return \file_get_contents($target);
            }
        );
    }

    public function tempFile(callable $callback, string $prefix = 'favicon-tmp')
    {
        $tempSource = \tempnam(\sys_get_temp_dir(), $prefix);
        if ($tempSource === false) {
            throw new \UnexpectedValueException('Cannot create temporary file');
        }

        $tempTarget = \tempnam(\sys_get_temp_dir(), $prefix);
        if ($tempTarget === false) {
            \unlink($tempSource);
            throw new \UnexpectedValueException('Cannot create temporary file');
        }

        try {
            return $callback($tempSource, $tempTarget);
        } finally {
            \unlink($tempSource);
            \unlink($tempTarget);
        }
    }

    public static function cliImageMagick6(Input $input): self
    {
        return new self ($input, 'convert');
    }

    public static function cliImageMagick7(Input $input): self
    {
        return new self ($input, 'magick');
    }

    public static function cliDetectImageMagickVersion(Input $input): self
    {
        $version = \Imagick::getVersion();
        if (str_starts_with($version['versionString'], 'ImageMagick 7')) {
            return self::cliImageMagick7($input);
        }

        if (str_starts_with($version['versionString'], 'ImageMagick 6')) {
            return self::cliImageMagick6($input);
        }

        throw new \RuntimeException('Failed to detect ImageMagick version, version: ' . $version['versionString']);
    }
}
