<?php

	session_start();
	
	if ((isset($_SESSION['online'])) && ($_SESSION['online']==true))
	{
		header('Location: forum.php');
		exit();
	}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title></title>
	
	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="style2.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Kalam" rel="stylesheet">

	
</head>
<body>
<h1>Info-Forum</h1>
	<div id="content">
		<form action="login.php" method="post">
		
			Login: <br /> <input type="text" name="login" value="<?php
				if (isset($_SESSION['login'])){
				echo $_SESSION['login'];
				unset($_SESSION['login']);
			}
			?>"/></> <br />
			Hasło: <br /> <input type="password" name="password" /> <br /><br />
			<input type="submit" value="Zaloguj się" />
		
		</form>
		
		<?php
		if(isset($_SESSION['error_login']) && $_SESSION['error_login']==true)
		{
			echo "<p>Błędny Login lub Hasło<p>";
			$_SESSION['error_login']=false;
			
		}
		?>
		<a href='register.php'>Rejestracja</a>
	</div>
		<div id="footer">© by Michał Kubiak</div>


</body>
</html>