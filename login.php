<?php

require __DIR__.'/vendor/autoload.php';

use Tarantool\Client\Client;
use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;

/*$Ulogin=$_POST["login"];
$Upassword=$_POST["password"];*/
$Ulogin=1;
$Upassword=1;
$host = 'localhost'; 
$database = 'test'; 
$user = 'root';
$password = ''; 
$mysqli = new mysqli($host, $user, $password, $database);
$qu='select id, password from password where login="'.mysqli_real_escape_string($mysqli,$Ulogin).'"';
$query = mysqli_query($mysqli,$qu);
$data = mysqli_fetch_assoc($query);
if($data['password'] === md5(md5($Upassword)))
    {
    $string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $hash = "";
    $clen = strlen($string) - 1;
    while (strlen($hash) < 6) {
        $hash .= $string[mt_rand(0,$clen)];
    }

    $conn = new StreamConnection('0.0.0.0:3311');
    $client = new Client($conn, new PurePacker());
	$space = $client->getSpace('lab5');

	$result = $space->select([(int)$data['user_id']]);
	$ehash = $result->getData()[0][1];
	if($ehash == "")
	{
        	$space->insert([(int)$data['user_id'], $hash]);
	}
	else
	{
		$hash = $ehash;
	}
    setcookie("id", $data['user_id'], time()+60*60*24*30);
    setcookie("hash", $hash, time()+60*60*24*30,null,null,null,true);
	echo "g";
}

mysqli_close($mysqli);
?>