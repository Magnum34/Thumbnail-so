<?php

use PHPUnit\Framework\TestCase;
use ThumbnailSo\Exceptions\ThumbnailSoException;
use ThumbnailSo\ThumbnailSo;

class ThumbnailSoTest extends TestCase {


    private $image_types = array(
        'jpeg',
        'png'
    );

    private function createImage($width, $height, $type){

        if (!in_array($type, $this->image_types)) {
            throw new ThumbnailSoException('Unsupported image type');
        }

        $image = imagecreatetruecolor($width, $height);

        $filename = tempnam(sys_get_temp_dir(), 'resize_test_image');

        $output_function = 'image' . $type;
        $output_function($image, $filename);

        return $filename;
    }

    /**
    * @expectedException ThumbnailSo\Exceptions\ThumbnailSoException
    * @expectedExceptionMessage File does not exist
    */
    public function testLoadNoFile(){
        new ThumbnailSo('');
    }

    public function testLoadImageJPG(){
        $image = $this->createImage(1, 1, 'jpeg');
        $thumbnail = new ThumbnailSo($image);

        $this->assertEquals(IMAGETYPE_JPEG, $thumbnail->getSourceType());
    }

    public function testLoadImagePNG(){
        $image = $this->createImage(1, 1, 'png');
        $thumbnail = new ThumbnailSo($image);

        $this->assertEquals(IMAGETYPE_PNG, $thumbnail->getSourceType());
    }
}