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
* [Save AWS S3](#save-aws-s3)
* [Extension new providers](#extension-new-providers)

## Requirements

* PHP >=7.1 

## Setup

```
composer require magnum34/thumbnail-so
```
## Methods 

1. resizeToMaxSide($max)

* max -  The size of the long side of the image after scaling is max pixels.

2. save($type, $directorty, $filename)
* type - provider type default: local, s3.
* directory - target path to save.


## Save Local Storage


Example:
```php
use ThumbnailSo\ThumbnailSo;

$img = new ThumbnailSo('image.jpeg');
$img->resizeToMaxSide(150);
$img->save('local', 'example', 'image');

```

## Save AWS S3


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
```php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__,'.env');
$dotenv->load();

use ThumbnailSo\ThumbnailSo;

$img = new ThumbnailSo('image.jpeg');
$img->resizeToMaxSide(150);
$img->save('s3', 'example', 'image');

```

## Extension new providers

Example Dropbox:

1. Install Dropbox PHP SDK ->  https://github.com/kunalvarma05/dropbox-php-sdk

```
php composer require kunalvarma05/dropbox-php-sdk
```

2. Create Driver for Dropbox. 
DropboxDriver.php
```php

<?php

namespace Dropbox;

use ThumbnailSo\DriverManagerInterface;
use ThumbnailSo\Exceptions\ThumbnailSoException;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;

class DropboxDriver implements DriverManagerInterface {

    // Name driver - provider
    public function getName(){
        return 'dropbox';
    }

    // Name configuration driver
    public function getConfigName(){
        return "dropbox";
    }

    // data configuration driver
    public function getConfig(){
        return [
            'client_id' => $_ENV['DROPBOX_CLIENT_ID'],
            'client_secret' => $_ENV['DROPBOX_CLIENT_SECRET'],
            "token" => $_ENV['DROPBOX_ACCESS_TOKEN']
        ];

    }

    // Record rule for the driver
    public function afterSave(string $source_image, string $destination_dir, string $destination_name, string $extension){
        $config = $this->getConfig();
        try {
            $app = new DropboxApp($config['client_id'], $config['client_secret'],$config['token']);
            $dropbox = new Dropbox($app);
            $dropboxFile = new DropboxFile($source_image);
            $dropbox->simpleUpload($dropboxFile , "/$destination_dir/{$destination_name}.{$extension}", ['autorename' => true]);
           
        }catch(\Exception $exc){
            throw new ThumbnailSoException($exc->getMessage());
        }

    }
}
```

3. Add environment variables to file .env

```
DROPBOX_CLIENT_ID="PUT_THE_CLIENT_ID"
DROPBOX_CLIENT_SECRET="PUT_THE_CLIENT_SECRET"
DROPBOX_ACCESS_TOKEN="PUT_THE_ACCESS_TOKEN"
```

4. Launch Thumbnail so for dopbox.

```php

require __DIR__ . '/vendor/autoload.php';
require('./DropboxDriver.php');

use ThumbnailSo\ThumbnailSo;
use ThumbnailSo\RegisterDriver;
use Dropbox\DropboxDriver;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__,'.env');
$dotenv->load();

// Register driver for Dropbox
$dropbox = new DropboxDriver();
RegisterDriver::set('dropbox',$dropbox );

$img = new ThumbnailSo('./cat.jpeg');
$img->resizeToMaxSide(150);
$img->save('dropbox', 'example', 'cat');


```


## License

Thumbnail So is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2021 **Magnum34**