<?php
	$id=$_POST["id"];
	$directory ="./".$_POST["dir"];

    $allowed_types=array("jpg", "png", "gif");  //разрешеные типы изображений
    $file_parts = array();
      $ext="";
      $title="";
      $i=0;
    //пробуем открыть папку
      $dir_handle = @opendir($directory) or die("Ошибка при открытии папки !!!");
    while ($file = readdir($dir_handle))    //поиск по файлам
      {
      if($file=="." || $file == "..") continue;  //пропустить ссылки на другие папки
      $file_parts = explode(".",$file);          //разделить имя файла и поместить его в массив
      $ext = strtolower(array_pop($file_parts));   //последний элеменет - это расширение


      if(in_array($ext,$allowed_types))
      {
		if($file==$id || $id=="all"){
      echo '<div class = "block_img" id="'."full".$file.'">
                <img src="'.$directory.'/'.$file.'" id="'.$file.'" class="pimg" title="'.$file.'" />
            </div>';
		}
     $i++;
      }

      }
    closedir($dir_handle);  //закрыть папку
    ?>