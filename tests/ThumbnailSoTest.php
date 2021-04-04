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


    public function ResizeToMaxSideDataProvider(){
        return [
            [[600, 400], [150, 100]],
            [[400, 400], [150, 150]],
            [[150, 150], [150, 150]],
            [[100, 50], [100, 50]]
        ];
    }

    /**
    * @expectedException \ThumbnailSo\Exceptions\ThumbnailSoException
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

    /**
     * @dataProvider ResizeToMaxSideDataProvider
     */
    public function testResizeToMaxSidePNGSuccess(array $source , array $target){
        $image = $this->createImage($source[0], $source[1], 'png');
        $thumbnail = new ThumbnailSo($image);
        $thumbnail->resizeToMaxSide(150);

        $this->assertEquals($target[0], $thumbnail->getDestinationWidth());
        $this->assertEquals($target[1], $thumbnail->getDestinationHeight());

    }

    /**
     * @dataProvider ResizeToMaxSideDataProvider
     */
    public function testResizeToMaxSideJPEGSuccess(array $source , array $target){
        $image = $this->createImage($source[0], $source[1], 'jpeg');
        $thumbnail = new ThumbnailSo($image);
        $thumbnail->resizeToMaxSide(150);

        $this->assertEquals($target[0], $thumbnail->getDestinationWidth());
        $this->assertEquals($target[1], $thumbnail->getDestinationHeight());

    }

    public function testSaveLocalJPEGSuccess(){
        $image = $this->createImage(600, 400, 'jpeg');
        $thumbnail = new ThumbnailSo($image);
        $thumbnail->resizeToMaxSide(150);
        $temp_dir = sys_get_temp_dir();
        $thumbnail->save('local', $temp_dir , 'example');
        $this->assertEquals(IMAGETYPE_JPEG, exif_imagetype($temp_dir .'/example.jpeg'));
    }

    public function testSaveLocalPNGSuccess(){
        $image = $this->createImage(600, 400, 'png');
        $thumbnail = new ThumbnailSo($image);
        $thumbnail->resizeToMaxSide(150);
        $temp_dir = sys_get_temp_dir();
        $thumbnail->save('local', $temp_dir , 'example');
        $this->assertEquals(IMAGETYPE_PNG, exif_imagetype($temp_dir .'/example.png'));
    }
    
}