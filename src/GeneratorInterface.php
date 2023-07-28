<?php

declare(strict_types=1);

namespace Genkgo\Favicon;

interface GeneratorInterface
{
    /**
     * @throws \ImagickException
     */
    public function generate(): string;
}
