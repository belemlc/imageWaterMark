<?php

class ImageWaterMark {

    private $image = null;
    private $logo_mark = 'logoin.png';
    private $padding = 10;
    private $opacity = 80;
    private $percent = 0.2;
    private $output = '';
    private $image_size = array();

    public function __construct(string $image) {
        $this->image = $image;
    }

    public function make() {
        echo '<pre>', var_dump(getimagesize($this->image));
    }

}