<?php
session_start();

if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}
//PL wczytuje objekt baze danych EN
//EN read the database object
require_once "database.php";

//PL filtruje hasło i login podane przez uzytkownika
//EN filter the password and login provided by the user
$login = filter_input(INPUT_POST, 'login');
$password = filter_input(INPUT_POST, 'password');
//PL podaje "password" kwerendy z tabeli "users" o zmiennej "$login" z wiersza login
//EN gives the "password" query from the "users" table with the variable "$ login" from the login line
$userQuery = $db->prepare('SELECT id, password FROM users WHERE login = :login');
$userQuery->bindValue(':login', $login, PDO::PARAM_STR);
$userQuery->execute();

$user = $userQuery->fetch();
//PL hashowanie Hasła podanego od uzytkownika
//EN password hashing given from the user
$pssword_hash = password_hash($password, PASSWORD_DEFAULT);
//PL weryfikacja hasła
//EN password verification
if ($user && password_verify($password, $user['password']))
	{
	header('Location: forum.php');
	$_SESSION['online']=true;
	$_SESSION['nick']=$login;
	}
	else
	{
	header('Location: login.php');
	$_SESSION['error_login']=true;
	$_SESSION['login']=$login;
	}


?>