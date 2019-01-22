<?php

require __DIR__.'/vendor/autoload.php';

use Tarantool\Client\Client;
use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;


$Ulogin=$_POST["login"];
$Upassword=$_POST["password"];
$host = 'localhost'; 
$database = 'password'; 
$user = 'admin';
$password = 'password'; 
$mysqli = new mysqli($host, $user, $password, $database);
$qu='select id, password from password where login="'.mysqli_real_escape_string($mysqli,$Ulogin).'"';
$query = mysqli_query($mysqli,$qu);
$data = mysqli_fetch_assoc($query);
if($data['password'] === md5(md5(trim($Upassword))))
    {
	#generate hach for user
    	$string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    	$hash = "";
    	$clen = strlen($string) - 1;
    	while (strlen($hash) < 6) {
        	$hash .= $string[mt_rand(0,$clen)];
    	}
	#connect to tarantool (https://github.com/tarantool-php/client)
	$conn = new StreamConnection();
	$client = new Client($conn, new PurePacker());
  	$space = $client->getSpace('example');
	$result = $space->select([(int)$data['id']]);
	$ehash="";
	$ehash = $result->getData()[0][1];
	var_dump($_COOKIE['hash']);
	var_dump($_COOKIE['id']);
	var_dump($ehash);
	var_dump($data['id']);
	#chack coockie
	if ($ehash!="")
	{
		if($ehash !== $_COOKIE['hash'] || $data['id']!==$_COOKIE['id'])
    		{
			
        		setcookie("id", $data['id'], time()+3600/*1hour*/);
        		setcookie("hash", $ehash, time()+3600,null,null,null,true);
        		echo "first time log in a row";
    		}
    		else
    		{
			setcookie("id", $data['id'], time()+3600/*1hour*/);
        		setcookie("hash", $ehash, time()+3600,null,null,null,true);
        		echo "second log in a row";
    		}
	}
	else 
	{
		$space->insert([(int)$data['id'], $hash]);
		echo "first log";
	}

}
else
{
	echo "not login";
}

mysqli_close($mysqli);
?>
