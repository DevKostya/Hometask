<?php
	$Ulogin=$_POST["login"];
	$Upassword=$_POST["password"];
	$host = 'localhost'; 
	$database = 'password'; 
	$user = 'admin';
	$password = 'password';
	$mysqli = new mysqli($host, $user, $password, $database);
	$qu='SELECT id FROM password WHERE login="'.mysqli_real_escape_string($mysqli, $Ulogin).'"';
    	$query = mysqli_query($mysqli,$qu);
	$rownum=$query->num_rows;
	if($rownum == 0)
    	{	
		$qu="INSERT INTO password values(default,'".$Ulogin."','".md5(md5(trim($Upassword)))."')";
        	mysqli_query($mysqli,$qu);
        	echo "g";
    	}
    	if($rownum>0){
        	echo "b";
    	}
?>
