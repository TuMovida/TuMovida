<?php
class Thumbnails
{
	public $srcFile, $newFile, $resized;
	public function __construct($imgFile)
	{
		$this->srcFile = $imgFile;
		$this->newFile = imagecreatefromjpeg($imgFile);
		if (!$this->newFile || (!file_exists($this->srcFile))){
			$im  = imagecreatetruecolor(150, 30);
	        $fondo = imagecolorallocate($im, 255, 255, 255);
	        $ct  = imagecolorallocate($im, 0, 0, 0);
	        imagefilledrectangle($im, 0, 0, 150, 30, $fondo);
	        imagestring($im, 1, 5, 5, 'Error cargando ' . $imagen, $ct);
	        return $im;
		}
		return $this->newFile;
	}
	public function show($new_width = 450)
	{
		list($width, $height) = getimagesize($this->srcFile);
        if ($new_width === NULL){
        	$new_width = round(($width * $new_height) / $height);	
        }else{
        	$new_height = round(($height * $new_width) / $width);	
        }
        $this->resized = imagecreatetruecolor($new_width, $new_height);
	    imagecopyresampled($this->resized, $this->newFile, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	    $resized = imagejpeg($this->resized, null, 80);
	    //imageDestroy($resized);
		return $resized;
	}
}
if (isset($_GET['s']) && file_exists($_GET['s']))
{
	$src = $_GET['s'];
	$width = (isset($_GET['w'])) ? $_GET['w'] : 450;
	$img = new Thumbnails($src);
	if ($img){
		@header("Content-Type: image/jpeg");
		$img->show($width);
	}else{
		echo "ERROR";
	}
}