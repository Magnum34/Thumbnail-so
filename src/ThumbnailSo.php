<?php

namespace ThumbnailSo;

use \ThumbnailSo\Drivers\AWSDriver;
use \ThumbnailSo\Exceptions\ThumbnailSoException;
use \ThumbnailSo\RegisterDriver;
use \ThumbnailSo\Drivers\LocalDriver;

class ThumbnailSo {


    protected $source_width;

    protected $quality = 100;

    protected $source_height;

    protected $source_type;

    protected $source_image;

    protected $tmp_dir;

    protected $destination_width;

    protected $destination_height;

    protected $drivers;

    /**
     * Getting data from an image e.g width, height, type
     * Type https://www.php.net/manual/en/function.exif-imagetype.php
     * @param string $filename
     * @return void
     */
    private function imageInfo(string $filename){
        $info = getimagesize($filename);

        if(!$info){
            throw new ThumbnailSoException('Could not read file');
        }

        $this->source_width = $info[0];
        $this->source_height = $info[1];
        $this->source_type = $info[2];

        switch($this->source_type){
            case IMAGETYPE_PNG:
                $this->source_image = imagecreatefrompng($filename);
                break;
            case IMAGETYPE_JPEG:
                $this->source_image = imagecreatefromjpeg($filename);
                break;
            default:
                throw new ThumbnailSoException('Unsupported image type');
        }

        $this->resize($this->source_width, $this->source_height);
    }

    /**
     * Initial Drivers
     *
     * @return void
     */
    private function initDrivers(){
        $this->drivers = RegisterDriver::allKeys();
    }

    /**
     * Loads image source 
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        if(empty($filename) || !is_file($filename)){
            throw new ThumbnailSoException('File does not exists');
        }
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        if(strstr(finfo_file($fileinfo, $filename), 'image') === false){
            throw new ThumbnailSoException('Unsupported file type');
        }
        $this->imageInfo($filename);

        $local = new LocalDriver();
        RegisterDriver::set($local->getName(), $local);

        $aws = new AWSDriver();
        RegisterDriver::set($aws->getName(),$aws);

        $this->initDrivers();
       
    }

    /**
     * Resizes image according to the given width and height
     *
     * @param integer $width
     * @param integer $height
     * 
     * @return this
     */
    public function resize(int $width, int $height){

        $this->destination_width = $width;
        $this->destination_height = $height;
        return $this;
    }

    /**
     * Resizes image to best fit inside the given dimensions
     *
     * @param integer $max
     * @return void
     */
    public function resizeToMaxSide(int $max){

        if($max == 0){
            throw new ThumbnailSoException("Can't be zero");
        }

        if($this->getSourceHeight() <= $max && $this->getSourceHeight() <= $max){
            return $this;
        }

        if($this->getSourceHeight() > $this->getSourceWidth()){
            $ratio = $this->getSourceHeight() / $max;
        }else{
            $ratio = $this->getSourceWidth() / $max;
        }

        $width = (int) floor($this->getSourceWidth() / $ratio);
        $height = (int) floor($this->getSourceHeight() / $ratio);

        $this->resize($width, $height);

        return $this;
    }


    /**
     * Save new image (Thumbnail)
     *
     * @param string $driver
     * @param string $destination_dir
     * @return void
     */
    public function save(string $driver, string $destination_dir, string $destination_name){

        switch($this->source_type){
            case IMAGETYPE_PNG:
                $dest_image = imagecreatetruecolor($this->getDestinationWidth(), $this->getDestinationHeight());
                imagealphablending($dest_image, false);
                imagesavealpha($dest_image, true);
                $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 127);
                imagecolortransparent($dest_image, $background);
                imagefill($dest_image, 0, 0, $background);
                break;
            case IMAGETYPE_JPEG:
                $dest_image = imagecreatetruecolor($this->getDestinationWidth(), $this->getDestinationHeight());
                $background = imagecolorallocate($dest_image, 255, 255, 255);
                imagefilledrectangle($dest_image, 0, 0, $this->getDestinationWidth(), $this->getDestinationHeight(), $background);
                break;
            default:
                throw new ThumbnailSoException('Unsupported image type');
        }

        imagecopyresampled(
            $dest_image,
            $this->source_image,
            0,
            0,
            0,
            0,
            $this->getDestinationWidth(),
            $this->getDestinationHeight(),
            $this->getSourceWidth(),
            $this->getSourceHeight()
        );

        $this->tmp_dir  = tempnam(sys_get_temp_dir(), '');

        switch($this->source_type){
            case IMAGETYPE_PNG:
                imagepng($dest_image, $this->getTmpDir() , 0);
                $extension = 'png';
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($dest_image,$this->getTmpDir(), $this->getQuality());
                $extension = 'jpeg';
                break;

        }

        if(!in_array($driver,$this->drivers)){
            throw new ThumbnailSoException('Invalid driver name');
        }


        $driver = RegisterDriver::get($driver);

        $driver->afterSave($this->getTmpDir(), $destination_dir, $destination_name, $extension);

        imagedestroy($dest_image);

        return $this;

    }

    /**
     * Gets source width
     *
     * @return integer
     */
    public function getSourceWidth(): int{
        return $this->source_width;
    }

    /**
     * Gets source height
     *
     * @return integer
     */
    public function getSourceHeight(): int {
        return $this->source_height;
    }

    /**
     * Gets destination width
     *
     * @return integer
     */
    public function getDestinationWidth(): int {
        return $this->destination_width;
    }

    /**
     * Gets destination height
     *
     * @return integer
     */
    public function getDestinationHeight(): int {
        return $this->destination_height;
    }

    /**
     * Gets quality image
     *
     * @return integer
     */
    public function getQuality(): int {
        return $this->quality;
    }


    public function getSourceType(){
        return $this->source_type;
    }

    /**
     * Gets temporary dir 
     *
     * @return string
     */
    public function getTmpDir(): string{
        return $this->tmp_dir;
    }


}