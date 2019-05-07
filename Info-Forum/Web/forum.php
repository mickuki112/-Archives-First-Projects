<?php

	session_start();
	
	if (!isset($_SESSION['online']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "database.php";
	if (isset($_POST['theNameEatku'])){
		$correctValidation=true;
		$table = filter_input(INPUT_POST, 'theNameEatku');
		$table = preg_replace('/ +/','_',$table);
		if (is_numeric($table)){
			$correctValidation=false;
			$_SESSION['errorNewTable']="Tytuł wątku nie może zawierać samych cyfr";
		}
		echo $table;
		$content = filter_input(INPUT_POST, 'content');
			//validation-----------------
		if (!((strlen($table)>3) && (strlen($table)<20)))
		{$correctValidation=false;
		$_SESSION['errorNewTable']="Tytuł wątku musi posiadać od 3 do 20 znaków!";
		}
		if (!(strlen($content)>3))
		{$correctValidation=false;
		$_SESSION['errorNewTable']="Trzeba wypełnić pole Treść";
		}
		if ($table=="title" || $table=="users")
		{
		$correctValidation=false;
		$_SESSION['errorNewTable']="Błedny tytuł";
		}
		
		$userQuery = $db->prepare('SELECT id, titleTable FROM title WHERE titleTable = :titleTable');
		$userQuery->bindValue(':titleTable', $table, PDO::PARAM_STR);
		$userQuery->execute();
		$num_rows = $userQuery->rowCount();
		if($num_rows>0)
			{
				$correctValidation=false;
				$_SESSION['errorNewTable']="Istnieje już wątek o takiej nazwie";
			}
			
		if($correctValidation==true)
		{
			//creating table
			$sql ="CREATE table $table(
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			date date NOT NULL, 
			contents VARCHAR( 5000 ) NOT NULL,
			nick VARCHAR( 21 ) NOT NULL)";
			$db->exec($sql);
			print("Created $table Table.\n");

			$query = $db->prepare('INSERT INTO title VALUES (NULL, :titleTable)');
			$query->bindValue(':titleTable', $table, PDO::PARAM_STR);
			$query->execute();
			
			
			$query = $db->prepare('INSERT INTO '.$table.' VALUES (NULL, :date, :contents, :nick)');
			$query->bindValue(':date', date("Y-m-d"), PDO::PARAM_STR);
			$query->bindValue(':contents', $content, PDO::PARAM_STR);
			$query->bindValue(':nick', $_SESSION['nick'], PDO::PARAM_STR);
			$query->execute();
		}
	}
	
	
	
	$usersQuery = $db->query('SELECT * FROM title');
	$tablelist = $usersQuery->fetchAll();
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	


	
</head>
<body>
	<a href='forum.php'><h1>Info-Forum</h1></a>
	<a href="logout.php" class="logout">Wyloguj</a>
	<div id="content">
		<div class="listEtaku">
			<?php
				$i=0; //loop counter
				foreach ($tablelist as $tablelist) {
				echo "<div class='rowList' id='{$tablelist['titleTable']}'>{$tablelist['titleTable']}</div>";
				echo "<form method='POST' action='eatku.php'>
						  <input type='hidden' name='nameTableList' value='{$tablelist['titleTable']}'/>
							  <script type='text/javascript'>						
										$('#{$tablelist['titleTable']}').click(function(e) {
											document.forms[{$i}].submit();
										});
								
							 </script>
					 </form>";
				$i++;
				}
			?>
		</div>
		<div class="newEtaku">
			<form  method="post">
				Tytuł nowego wątku: <br /> <input type="text" name="theNameEatku" /></> <br />
				Treść:</br> <textarea name="content" id='contentInput' cols="x" rows="y"></textarea>
				</br>
				<input type="submit" value="Stwórz nowy wątek" />
				<?php
				if (isset($_SESSION['errorNewTable']))
					{
						echo '<div class="error">'.$_SESSION['errorNewTable'].'</div>';
						unset($_SESSION['errorNewTable']);
					}
				?>
			</form>
		</div>
	</div>
	<div id="footer">© by Michał Kubiak</div>
	
</body>
</html>