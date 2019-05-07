<?php

	session_start();
	
	if (isset($_POST['email']))
	{
		
		$correctValidation=true;
		
		$nick = $_POST['nick'];
		
		//validation nick
		if ((strlen($nick)<3) || (strlen($nick)>20))
		{
			$correctValidation=false;
			$_SESSION['errorNick1']="-Nick musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($nick)==false)
		{
			$correctValidation=false;
			$_SESSION['errorNick2']="-Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		
		//validation email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$correctValidation=false;
			$_SESSION['errorEmail']="-Podaj poprawny adres e-mail!";
		}
		
		//validation hasła
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if ((strlen($password1)<8) || (strlen($password1)>20))
		{
			$correctValidation=false;
			$_SESSION['errorPassword1']="-Hasło musi posiadać od 8 do 20 znaków!";
		}
		if (ctype_alnum($password1)==false)
		{
			$correctValidation=false;
			$_SESSION['errorPassword3']="-Hasło może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		if ($password1!=$password2)
		{
			$correctValidation=false;
			$_SESSION['errorPassword2']="-Podane hasła nie są identyczne!";
		}	
		
		//validation Regulations
		if (!isset($_POST['regulations']))
		{
			$correctValidation=false;
			$_SESSION['errorRegulations']="-Potwierdź akceptację regulaminu!";
		}
		//----
		$_SESSION['nick'] = $nick;
		$_SESSION['email'] = $email;
		$_SESSION['password1'] = $password1;
		$_SESSION['password2'] = $password2;
		if (isset($_POST['regulations'])) $_SESSION['regulations'] = true;
		//validation E-mail
		if($correctValidation==true)
			{
			require_once "database.php";
			$userQuery = $db->prepare('SELECT id, password FROM users WHERE email = :email');
			$userQuery->bindValue(':email', $email, PDO::PARAM_STR);
			$userQuery->execute();
			$num_rows = $userQuery->rowCount();
			if($num_rows>0)
				{
					$correctValidation=false;
					$_SESSION['errorEmail']="Istnieje już konto przypisane do tego adresu e-mail!";
				}
			}
		//validation Nick
		if($correctValidation==true)
			{
			require_once "database.php";
			$userQuery = $db->prepare('SELECT id, password FROM users WHERE login = :login');
			$userQuery->bindValue(':login', $nick, PDO::PARAM_STR);
			$userQuery->execute();
			
			$num_rows = $userQuery->rowCount();
			if($num_rows>0)
				{
					$correctValidation=false;
					$_SESSION['errorNick1']="Istnieje już gracz o takim nicku! Wybierz inny.";
				}				
			}
		//saving the user
		if($correctValidation==true)
			{
		$haslo_hash = password_hash($password1, PASSWORD_DEFAULT);
			
		$query = $db->prepare('INSERT INTO users VALUES (NULL, :login, :password, :email)');
		$query->bindValue(':login', $nick, PDO::PARAM_STR);
		$query->bindValue(':password', $haslo_hash, PDO::PARAM_STR);
		$query->bindValue(':email', $email, PDO::PARAM_STR);
		$query->execute();
		header('Location: index.php');
			}
		
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
	<a href='forum.php'><h1>Info-Forum</h1></a>
<div id='content'>
	<form method="post">
		Twój Login: <br /> <input type="text" name="nick" value="<?php
			if (isset($_SESSION['nick'])){
				echo $_SESSION['nick'];
				unset($_SESSION['nick']);
			}
		?>"/><br />
		Twoje hasło: <br /> <input type="password" name="password1" value="<?php
				if (isset($_SESSION['password1'])){
				echo $_SESSION['password1'];
				unset($_SESSION['password1']);
			}
		?>"/><br />
		Powtórz hasło: <br /> <input type="password" name="password2" value="<?php
				if (isset($_SESSION['password2'])){
				echo $_SESSION['password2'];
				unset($_SESSION['password2']);
			}
		?>"/><br />
		Twój E-mail: <br /> <input type="text" name="email" value="<?php
				if (isset($_SESSION['email'])){
				echo $_SESSION['email'];
				unset($_SESSION['email']);
			}
		?>"/><br />
		<label>
			<input type="checkbox" name="regulations" <?php
				if (isset($_SESSION['regulations']))
				{
					echo "checked";
					unset($_SESSION['regulations']);
				}
					?>/> Akceptuję regulamin
		</label>
		<?php
		$errorTable[0] = "errorNick1";
		$errorTable[1] = "errorNick2";
		$errorTable[2] = "errorEmail";
		$errorTable[3] = "errorPassword1";
		$errorTable[4] = "errorPassword2";
		$errorTable[5] = "errorPassword3";
		$errorTable[6] = "errorRegulations";
		for($i=0;7>$i;$i++)
			if (isset($_SESSION[$errorTable[$i]]))
				{
				echo '<div class="error">'.$_SESSION[$errorTable[$i]].'</div>';
				unset($_SESSION[$errorTable[$i]]);
				}
		?>
		</br>
		<input type="submit" value="Zarejestruj się" />	
	</div>
</form>
	<div id="footer">© by Michał Kubiak</div>
</body>
</html>