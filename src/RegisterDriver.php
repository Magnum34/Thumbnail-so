<?php

namespace ThumbnailSo;

use ThumbnailSo\Exceptions\ThumbnailSoException;
use ThumbnailSo\DriverManagerInterface;

class RegisterDriver {

    private static $drivers = [];

    /**
     * registration of the respective drivers
     *
     * @param string $key
     * @param DriverManagerInterface $driver
     * @return void
     */
    public static function set(string $key, DriverManagerInterface $driver ){
        self::$drivers[$key] = $driver;
    }


    /**
     * Get drivers
     *
     * @param string $key
     * @return DriverManagerInterface
     */
    public static function get(string $key){
        if(!array_key_exists($key, self::$drivers)){
            throw new ThumbnailSoException('Invalid key given');
        }

        return self::$drivers[$key];
    }

    /**
     * All drivers
     *
     * @return array
     */
    public static function all(){
        return self::$drivers;
    }

    /**
     * All name drivers
     *
     * @return array
     */
    public static function allKeys(){
        return array_keys(self::$drivers);
    }

}