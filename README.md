# Thumbnail So

[![Build Status](https://travis-ci.com/Magnum34/Thumbnail-so.svg?branch=main)](https://travis-ci.com/Magnum34/Thumbnail-so)
[![codecov](https://codecov.io/gh/Magnum34/Thumbnail-so/branch/main/graph/badge.svg?token=uNydmJRFvB)](https://codecov.io/gh/Magnum34/Thumbnail-so)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)



Thumbnail So is a PHP image manipulation library  for resize to max side for jpeg and png.
Expandable to different file storage providers e.g Dropbox.
Default is Local storage, AWS S3.

## Getting started

* [Requirements](#requirements)
* [Setup](#setup)
* [Methods](#methods)
* [Save Local Storage](#save-local-storage)
* [Extension new providers](#extension-new-providers)

## Requirements

* PHP >=7.1 

## Setup

```
composer require magnum34/thumbnail-so
```
## Methods 

1. resizeToMaxSide($max)

* max -  The size of the long side of the image after scaling is max.

2. save($type, $directorty, $filename)
* type - provider type default: local, s3.
* directory - target path to save.


## Save Local Storage


Example:
```
use ThumbnailSo\ThumbnailSo;

$img = new ThumbnailSo('image.jpeg');
$img->resizeToMaxSide(150);
$img->save('local', 'example', 'image');

```

## Save AWS S3 - ResizeToMaxSide


```
cp .env.example .env
```

Configuration for AWS S3.
```
AWS_ACCESS_KEY_ID="PUT_THE_ACCESS_KEY_ID"  
AWS_SECRET_ACCESS_KEY="PUT_THE_SECRET_ACCESS_KEY"
AWS_DEFAULT_REGION="PUT_THE_SELECTED_REGION_CODE"
AWS_BUCKET="PUT_YOUR_BUCKET_NAME"
```

Example:
```
use ThumbnailSo\ThumbnailSo;

$img = new ThumbnailSo('image.jpeg');
$img->resizeToMaxSide(150);
$img->save('s3', 'example', 'image');

```

## Extension new providers


## License

Thumbnail So is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2021 **Magnum34**