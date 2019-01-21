<?php
$_Image = 'C:/xampp/htdocs/image/';
$_Imagemini = 'C:/xampp/htdocs/imagemini/';

$files = scandir($_Image);
$countEdit = 0;

foreach ($files as $key => $photoname){
    if($photoname!='.' && $photoname!='..') {

    	$minifiedName = str_replace(".jpg", ".min.jpg", $photoname);
    	$minifiedPath = $_Imagemini.$minifiedName;

    	if (!file_exists($minifiedPath)) {
    		$minifiedImage = new Imagick($_Image.$photoname);
			$minifiedImage->adaptiveResizeImage(100,100);

			$minifiedImageFile = fopen($minifiedPath, "w");
			$minifiedImage->writeImageFile($minifiedImageFile);

			$countEdit++;
    	}
    };
}

echo $countEdit;
?>