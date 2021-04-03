<?php

namespace ThumbnailSo\Drivers;

use ThumbnailSo\DriverManagerInterface;

class AWSDriver implements DriverManagerInterface {

    public function getName(){
        return 's3';
    }

    public function getConfigName(){
        return "s3";
    }

    public function getConfig(){
        return [];
    }

    public function afterSave(string $source_image, string $destination_dir, string $destination_name, string $extension){
  
    }
}