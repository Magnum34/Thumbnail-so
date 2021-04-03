<?php

namespace ThumbnailSo;

use ThumbnailSo\RegisterDriver;

interface  DriverManagerInterface {


    /**
     * Name driver - provider
     *
     * @return string
     */
    public function getName();

    /**
     * Name configuration driver
     *
     * @return string
     */
    public function getConfigName();


    /**
     * data configuration driver
     *
     * @return array
     */
    public function getConfig();

    /**
     * Record rule for the driver
     *
     * @param string $destination_dir
     * @return void
     */
    public function afterSave(string $source_image,string $destination_dir, string $destination_name, string $extension);
}