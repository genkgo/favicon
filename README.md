# Genkgo/Favicon - Generate favicon for your website

[![Latest Version](https://img.shields.io/github/release/genkgo/favicon.svg?style=flat-square)](https://github.com/genkgo/favicon/releases)

Requires PHP 8.1+, and both the imagick and DOM extension.

## Create favicon package quick and easy

```php

use Genkgo\Favicon;

$outputDirectory = '/var/www/html/favicon';

$input = Favicon\Input::fromFile('/var/www/html/logo.png', InputImageType::PNG);
// or
$input = Favicon\Input::fromFile('/var/www/html/logo.svg', InputImageType::SVG);
// or
$input = Favicon\Input::letter('G', '#FFFFFF', '#00aaad');

$generator = new Favicon\FullPackageGenerator($input, '#FFFFFF');
foreach ($generator->package() as $fileName => $contents) {
    $pathName = $outputDirectory . '/' . $fileName;
    file_put_contents($pathName, $contents);
}
```

## Default package

- android-chrome-192x192.png
- android-chrome-512x512.png
- apple-touch-icon.png
- browserconfig.xml
- favicon.ico
- favicon.png
- favicon-16x16.png
- favicon-32x32.png
- favicon-48x48.ico
- favicon-48x48.png
- favicon-57x57.png
- favicon-76x76.png
- favicon-96x96.png
- favicon-128x128.png
- favicon-192x192.png
- favicon-228x228.png
- mstile-70x70.png
- mstile-150x150.png
- mstile-310x310.png

## Install using composer

```bash
$ composer require genkgo/favicon
```

## Tests

There are no tests. Maybe later.