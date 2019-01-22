<?php
	$Ulogin=$_POST["login"];
	$Upassword=$_POST["password"];
	$host = 'localhost'; 
	$database = 'test'; 
	$user = 'root';
	$password = ''; 
	$mysqli = new mysqli($host, $user, $password, $database);
    $query = mysqli_query($mysqli,'SELECT id FROM password WHERE login="'.mysqli_real_escape_string($mysqli, $Ulogin).'"');
	$rownum=$query->num_rows;
	if($rownum == 0)
    {	
		$qu="INSERT INTO password values(default,'".$Ulogin."','".md5(md5(trim($Upassword)))."')";
        mysqli_query($mysqli,$qu);
        echo "g";
    }
    if($rownum>0){
    {
        echo "b";
    }
    
	}
?>