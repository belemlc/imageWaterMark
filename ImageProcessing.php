<?php 
class ImageProcessing {
    /**
     * Caminho aonde a imagem será salva
     *
     * @var string
     */
    private $path;
    /**
     * Image que será tratada
     *
     * @var string
     */
    private $image;
    /**
     * Pega os detalhes da imagem definida
     *
     * @var object
     */
    private $imageDetails;
    /**
     * Define a largura da imagem a ser modificada
     *
     * @var int
     */
    private $imageResizeWidth;
    /**
     * Define a altura da imagem a ser modificada
     *
     * @var [type]
     */
    private $imageResizeHeight;
    /**
     * Define a qualidade da imagem 
     *
     * @var float
     */
    private $imageQuality;
    /**
     * Define o arquivo de imagem que será usado como marca d'agua
     *
     * @var string
     */
    private $logoWaterMark;

    private $logoWaterMarkAlign;

    const GIF = '1';
    const JPEG = '2';
    const PNG = '3';
    const WATER_MARK_ALIGN_BOTTOM_RIGHT = 10;
    const WATER_MARK_ALIGN_BOTTOM_LEFT = -10;


    public function getImageDetails() {
        $details = getimagesize($this->getImage());
        $objDetails = new stdClass();
        $objDetails->filename = pathinfo($this->getImage(), PATHINFO_BASENAME);
        $objDetails->width = $details[0];
        $objDetails->height = $details[1];
        $objDetails->type = $details[2];
        return $objDetails;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }
    
    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getImageResizeWidth() {
        return $this->imageResizeWidth;
    }

    public function setImageResizeWidth($imageResizeWidth) {
        $this->imageResizeWidth = $imageResizeWidth;
    }

    public function getimageResizeHeight() {
        return $this->imageResizeHeight;
    }

    public function setimageResizeHeight($imageResizeHeight) {
        $this->imageResizeHeight = $imageResizeHeight;
    }

    public function getimageQuality() {
        return $this->imageQuality;
    }

    public function setimageQuality($imageQuality) {
        $this->imageQuality = $imageQuality;
    }

    public function getlogoWaterMark() {
        return $this->logoWaterMark;
    }

    public function setlogoWaterMark($logoWaterMark) {
        $this->logoWaterMark = $logoWaterMark;
    }

    public function getlogoWaterMarkAlign() {
        return $this->logoWaterMarkAlign;
    }

    public function setlogoWaterMarkAlign($logoWaterMarkAlign) {
        $this->logoWaterMarkAlign = $logoWaterMarkAlign;
    }

    public function __construct() {

    }

    /**
     * Reduz o tamanho de uma imagem de acordo com as configurações definidas
     *
     * @return bool
     */
    public function reduceImagesize() {
        try {
            $image = '';
            // Cria uma imagem true color
            $imageCreated = imagecreatetruecolor($this->getImageResizeWidth(), $this->getimageResizeHeight());
            // Pega os detalhes da imagem
            $width = $this->getImageDetails()->width;
            $height = $this->getImageDetails()->height;
            $type = $this->getImageDetails()->type;
            $imagedir = $this->getPath().$this->getImageDetails()->filename;
            // Verifica o tipo da imagem
            if ($type == self::JPEG) {
                $image = imagecreatefromjpeg($this->getImage());
            } elseif ($type == self::PNG) {
                $image = imagecreatefrompng($this->getImage());
            } elseif ($type == self::GIF) {
                $image = imagecreatefromgif($this->getImage());
            }
            // Reduz a imagem para o tamanho informado
            imagecopyresampled($imageCreated, $image, 0, 0, 0, 0, $this->getImageResizeWidth(), $this->getimageResizeHeight(), $width, $height);
            // Retorna a imagem alterada
            return imagejpeg($imageCreated, $imagedir, 100);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Adiciona uma marca d'agua na imagem
     *
     * @return bool
     */
    public function createWaterMark() {
        // verifica se o arquivo existe
        $imagedir = $this->getPath().$this->getImageDetails()->filename;
        if (!is_readable($imagedir)) {
            throw new \Exception(' >>>>> arquivo ou diretório não existe.');
        }
        // Pega o logo pra usar como marca dagua
        $watermark = imagecreatefrompng($this->getlogoWaterMark());
        // Pega a imagem principal
        $image = imagecreatefromjpeg($this->getImage());
        // align image to bottom right
        if(empty($this->getlogoWaterMarkAlign())) {
            $this->setlogoWaterMarkAlign('bottom_right');
        }
        if ($this->getlogoWaterMarkAlign() === 'center') {
            $wx = imagesx($image)/2 - imagesx($watermark)/2;
            $wy = imagesy($image)/2 - imagesy($watermark)/2;
        }
        if ($this->getlogoWaterMarkAlign() === 'top_left') {
            $wx = (imagesx($image)/2 - imagesx($watermark))/10;
            $wy = (imagesy($image)/2 - imagesy($watermark))/10;
        }
        if ($this->getlogoWaterMarkAlign() === 'top_right') {
            
        }
        if ($this->getlogoWaterMarkAlign() === 'bottom_left') {
            //
        }
        if ($this->getlogoWaterMarkAlign() === 'bottom_right') {
            $wx = imagesx($image) - imagesx($watermark) - 10;
            $wy = imagesy($image) - imagesy($watermark) - 10;
        }
        imagecopy($image, $watermark, $wx, $wy, 0, 0, imagesx($watermark), imagesy($watermark));
        header('content-type: image/jpeg');
        imagejpeg($image, null, 100);
    }

    
}