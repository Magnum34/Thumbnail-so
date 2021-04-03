<?php

namespace ThumbnailSo;

use ThumbnailSo\Exceptions\ThumbnailSoException;
use ThumbnailSo\DriverManagerInterface;

class RegisterDriver {

    private static $drivers = [];

    public static function set(string $key, DriverManagerInterface $driver ){
        self::$drivers[$key] = $driver;
    }

    public static function get(string $key){
        if(!array_key_exists($key, self::$drivers)){
            throw new ThumbnailSoException('Invalid key given');
        }

        return self::$drivers[$key];
    }

    public static function all(){
        return self::$drivers;
    }

    public static function allKeys(){
        return array_keys(self::$drivers);
    }

}