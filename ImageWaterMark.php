<?php

class ImageWaterMark {

    private $image = null;
    private $logo = 'logoin.png';
    private $padding = 10;
    private $opacity = 80;
    private $quality = 100;
    private $percent = 0.2;
    private $width = '500';
    private $height = '500';
    private $output = './';
    private $image_data = array();

    public function __construct($image) {
        $this->image = $image;
        var_dump(pathinfo($image)); exit;
        $this->image_data = getimagesize($this->image);
    }

    private function reduceImagesize() {
        $result = false;
        
        list($width, $height, $type) = getimagesize($this->image);
        
        $imageIdentifier = imagecreatetruecolor($this->width, $this->height);
        $imageType = '';
        if ($this->getImageType($this->image) == 'jpeg') {
            $imageType = imagecreatefromjpeg($this->image);
        } elseif ($this->getImageType($this->image) == 'png') {
            $imageType = imagecreatefrompng($this->image);
        } elseif ($this->getImageType($this->image) == 'gif') {
            $imageType = imagecreatefromgif($this->image);
        }
        imagecopyresampled($imageIdentifier, $imageType, 0, 0, 0, 0, $this->width, $this->height, $width, $height);
        $this->output = $this->output.'foto_nova.jpeg';
        imagejpeg($imageIdentifier, $this->output, $this->quality);
        $this->createWaterMark($this->output);
    }

    private function createWaterMark($image) {
        $watermark = imagecreatefrompng($this->logo);
        $photo = imagecreatefromjpeg($image);
        // align image to bottom right
        $wx = imagesx($photo) - imagesx($watermark) - $this->padding;
        $wy = imagesy($photo) - imagesy($watermark) - $this->padding;
        imagecopy($photo, $watermark, $wx, $wy, 0, 0, imagesx($watermark), imagesy($watermark));
        //header('content-type: image/jpeg');
        imagejpeg($photo, 'tmp/foto_marked.jpeg', 100);
    }

    public function make() {
        $this->reduceImagesize();
        //$this->writeMark();
        //$this->createWaterMark();
    }

    private function getImageType($image) {
        list($width, $height, $_type) = getimagesize($image);
        $type = '';
        switch ($_type) {
            case 1:
                $type = 'gif';
                break; 
            case 2:
                $type ='jpeg';
                break;
            case 3:
                $type = 'png';
                break;
            default: $type = '';
        }

        return $type;
    }

}