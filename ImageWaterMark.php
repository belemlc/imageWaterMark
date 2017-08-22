<?php

class ImageWaterMark {

    private $image = null;
    private $logo_mark = 'logoin.png';
    private $padding = 10;
    private $opacity = 80;
    private $quality = 100;
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
        $result = imagejpeg($imageIdentifier, $this->output, $this->quality);
        return $result;
    }

    private function writeMark() {
        $imagem_original = "foto.jpg"; //nome da imagem original
        $logo_img = "logo.gif"; //nome da logo (utilize png ou gif com fundo transparente)
        $padding = 10; //define o espaço que a logo terá no lado esquerdo e na parte de baixo
        $opacidade = 80; //define a porcentagem de transparência da logo
        $logo = imagecreatefromgif($logo_img); //cria a logo
        $imagem = imagecreatefromjpeg($imagem_original); //cria a imagem original
        if(!$imagem || !$logo) die("Erro: imagem original ou logo não foram carregadas!"); //verificar se as imagens foram carregadas
          
        $logo_size = getimagesize($logo_img); //obtêm as dimensões da logo
        $logo_width = $logo_size[0]; //atribui a largura da logo
        $logo_height = $logo_size[1]; //atribui a altura da logo
        $imagem_size = getimagesize($imagem_original); //obtêm as dimensões da imagem original
        $dest_x = $imagem_size[0] - $logo_width - $padding;//define a posição horizontal que a logo se posicionará
        $dest_y = $imagem_size[1] - $logo_height - $padding;//define a posição vertical que a logo se posicionará
          
        imagecopymerge($imagem, $logo, $dest_x, $dest_y, 0, 0, $logo_width, $logo_height, $opacidade);//cópia marca d'água na imagem original
          
        // exibe a imagem com a marca d'água aplicada
        header("content-type: image/jpeg");
        imagejpeg($imagem);
        imagedestroy($imagem);
        imagedestroy($logo);
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