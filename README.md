# Genkgo/Favicon - Generate favicon for your website

[![Latest Version](https://img.shields.io/github/release/genkgo/favicon.svg?style=flat-square)](https://github.com/genkgo/favicon/releases)

Requires PHP 8.1+ and both the imagick and DOM extension.

## Install using composer

```bash
composer require genkgo/favicon
```


## Create favicon package quick and easy

```php

use Genkgo\Favicon;

$outputDirectory = '/var/www/html/favicon';

$input = Favicon\Input::fromFile('/var/www/html/logo.png', InputImageType::PNG);
// or add a different background color
$input = Favicon\Input::fromFile('/var/www/html/logo.png', InputImageType::PNG, '#FF0000');
// or use a svg as input
$input = Favicon\Input::fromFile('/var/www/html/logo.svg', InputImageType::SVG);
// or create a letter avatar
$input = Favicon\Input::digit('G', '#FFFFFF', '#00AAAD');

$generator = Favicon\FullPackageGenerator::newGenerator();
$manifest = new Favicon\WebApplicationManifest(
    Favicon\WebApplicationManifestDisplay::Standalone,
    'Title of website',
    'Short name of website',
    '#00AAAD', // theme color
    '#00AAAD', // tile color
);
foreach ($generator->package($input, $manifest, '/') as $fileName => $contents) {
    $pathName = $outputDirectory . '/' . $fileName;
    file_put_contents($pathName, $contents);
}

// append the head tags to your document
$document = new DOMDocument('1.0', 'UTF-8');
$html = $document->createElement('html');
$document->appendChild($html);

$head = $document->createElement('head');
foreach ($generator->headTags($document, $manifest, '/') as $tag) {
    $head->appendChild($tag);
}

// or just generate the tag strings
$tags = [];
$document = new DOMDocument('1.0', 'UTF-8');
foreach ($generator->headTags($document, $manifest, '/') as $tag) {
    $tags[] = $document->saveHTML($tag);
}
```

or use the command-line.

```bash
./vendor/bin/favicon-generator --help
./vendor/bin/favicon-generator 'Title of the website' file:public/logo.png output 
./vendor/bin/favicon-generator 'Title of the website' file:public/logo.png output --theme-color=#00AAAD --icon-background=#00AAAD --root=/
./vendor/bin/favicon-generator 'Title of the website' letter:G output
./vendor/bin/favicon-generator 'Title of the website' letter:G output --letter-color=#FFFFFF --theme-color=#00AAAD --icon-background=#00AAAD --root=/
```

## Full package

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
- favicon-512x512.png
- index.html
- mstile-70x70.png
- mstile-150x150.png
- mstile-310x310.png
- safari-pinned-tab.svg
- web-app-manifest.json

## Tests

There are no tests. Maybe later.
