<?php

require_once 'config.php';

try {
	
	$db = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8", $db_user, $db_password, [
		PDO::ATTR_EMULATE_PREPARES => false, 
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	]);
	
} catch (PDOException $error) {
	
	//echo $error->getMessage();
	exit('Database error');
	
}

