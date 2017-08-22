<?php

class ImageWaterMark {

    private $image = null;
    private $logo_mark = 'logoin.png';
    private $padding = 10;
    private $opacity = 80;
    private $quality = 85;
    private $percent = 0.2;
    private $width = '500';
    private $height = '500';
    private $output = 'tmp/';
    private $image_data = array();

    public function __construct(string $image) {
        $this->image = $image;
        $this->image_data = getimagesize($this->image);
    }

    private function adjustQuality(): bool {
        $result = false;
        
        list($width, $height, $type) = getimagesize($this->image);
        // $width = $this->width*$this->percent;
        // $height = $this->height*$this->percent;
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
        $result = imagejpeg($imageIdentifier, $this->output, 100);
        return $result;
    }

    public function make() {
        $this->adjustQuality();
    }

    private function getImageType($image): string {
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