<?php

namespace ThumbnailSo\Drivers;

use ThumbnailSo\DriverManagerInterface;

class LocalDriver implements DriverManagerInterface {

    public function getName(){
        return 'local';
    }

    public function getConfigName(){
        return "local";
    }

    public function getConfig(){
        return [];
    }

    public function afterSave(string $source_image, string $destination_dir, string $destination_name, string $extension){
        copy($source_image, "{$destination_dir}/{$destination_name}.{$extension}");
        unlink($source_image);
    }
}
