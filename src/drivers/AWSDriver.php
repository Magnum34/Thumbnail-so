<?php

namespace ThumbnailSo\Drivers;

use \ThumbnailSo\DriverManagerInterface;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use \ThumbnailSo\Exceptions\ThumbnailSoException;

class AWSDriver implements DriverManagerInterface {

    public function getName(){
        return 's3';
    }

    public function getConfigName(){
        return "s3";
    }

    public function getConfig(){
        return [
            'version' => 'latest',
            'acl' => 'public-read',
            'region' => $_ENV['AWS_DEFAULT_REGION'],
            'bucket' => $_ENV['AWS_BUCKET'],
            'key' => $_ENV['AWS_ACCESS_KEY_ID'],
            'secret' => $_ENV['AWS_SECRET_ACCESS_KEY']
        ];

    }

    public function afterSave(string $source_image, string $destination_dir, string $destination_name, string $extension){
        $config = $this->getConfig();
        try {
            $client = new S3Client([
                'version' => 'latest',
                'region' => $config['region'],
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret']
                ]
            ]);
            $client->putObject([
                'Bucket' => $config['bucket'],
                'ACL' => $config['acl'],
                'Key' =>  "{$destination_dir}/{$destination_name}.{$extension}",
                "Body" => fopen($source_image,'r')
            ]);

        }catch(S3Exception $exc){
            throw new ThumbnailSoException($exc->getMessage());
        }

    }
}