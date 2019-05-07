<?php

session_start();
	if (!isset($_SESSION['online']))
	{
		header('Location: index.php');
		exit();
	}
require_once "database.php";
$nameTableList = filter_input(INPUT_POST, 'nameTableList');	
	
$correctValidation=true;
$content = filter_input(INPUT_POST, 'content');
if (!(strlen($content)>3))
		{$correctValidation=false;
		$_SESSION['errorNew']="Trzeba wypełnić pole Treść";
		}
if($correctValidation==true){
	$query = $db->prepare('INSERT INTO '.$nameTableList.' VALUES (NULL, :date, :contents, :nick)');
	$query->bindValue(':date', date("Y-m-d"), PDO::PARAM_STR);
	$query->bindValue(':contents', $content, PDO::PARAM_STR);
	$query->bindValue(':nick', $_SESSION['nick'], PDO::PARAM_STR);
	$query->execute();
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
<link href="https://fonts.googleapis.com/css?family=Kalam" rel="stylesheet">

	
</head>
<body>
	<a href='forum.php'><h1>Info-Forum</h1></a>
	<a href="logout.php" class="logout">Wyloguj</a>
	<div id="content">
		<?php
			
			
			$usersQuery = $db->query('SELECT * FROM '.$nameTableList);
			$tablelist = $usersQuery->fetchAll();
			$i=0;
			foreach ($tablelist as $tablelist) {$marginForumList=$i%2;
						echo "	<div class='forumList , forumList{$marginForumList}'>
								<div class='topForumList'>
								{$tablelist['nick']}
								<date>{$tablelist['date']}</date>
								</div>
								{$tablelist['contents']}
								</div>";
								$i++;
						}
		?>
		<form method="POST">
		Treść:</br> <textarea name="content" id='contentInput' cols="x" rows="y"></textarea>
				<input type='hidden' type="text" name="nameTableList" value="<?php echo $nameTableList ;?>"/>
				</br>
				<input type="submit" value="Odpowiedz" />
		</form>
				<?php
				if (isset($_SESSION['errorNew']))
					{
						echo '<div class="error">'.$_SESSION['errorNew'].'</div>';
						unset($_SESSION['errorNew']);
					}
				?>
	</div>
		<div id="footer">© by Michał Kubiak</div>

</body>
</html>