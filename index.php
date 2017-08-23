<?php
ini_set('display_errors', true);

require 'ImageProcessing.php';

$photo = 'web_599c4b0ebe8b7.jpg';
$logo = 'logoin.png';
$image = new ImageProcessing();
$image->setPath('tmp/');
$image->setImage($photo);
$image->setlogoWaterMark($logo);
$image->setImageResizeWidth(500);
$image->setImageResizeHeight(500);
$image->reduceImagesize();
$imageResized = $image->getPath().$image->getImage();
$image->setImage($imageResized);
$image->createWaterMark();


// $walter = new ImageWaterMark($imagem);
// echo $walter->make();