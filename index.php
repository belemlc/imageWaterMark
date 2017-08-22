<?php
ini_set('display_errors', true);

require 'ImageWaterMark.php';

$imagem = 'foto.jpeg';

$walter = new ImageWaterMark($imagem);
echo $walter->make();