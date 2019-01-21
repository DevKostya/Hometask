<?php
$uploaddir = 'C:/xampp/htdocs/image/';
$uploadfile = $uploaddir . basename($_FILES['img']['name']);
move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile);
echo $basename($_FILES['img']['name']);
?>