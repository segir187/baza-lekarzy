<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UE-Compatible" content="IE=edge,chrome=1" />
	<title>Usuń lekarza</title>
</head>
<body>
	<div id="main">
	<div id="top_banner">
		<h1>Usuń lekarza</h1>
	</div>
	<div id="menu">
		<a href="index_school.php">Strona główna</a>
		<a href="display_school.php">Znajdź lekarzy</a>
		<a href="add_school.php">Dodaj lekarza</a>
		<a href="edit_school.php">Edytuj dane lekarza</a>
		<a href="delete_school.php">Usuń lekarza</a>
	</div>
	<div id="container">
		<?php
			/* procesowanie filtrow */
			error_reporting(0);
			function queryPrep($id)
			{
				if (strpos($id, ';') !== false or strpos($id, '-') !== false or strpos($id, '=') or strpos($id, '%') !== false or strpos($id, 'union') !== false or strpos($id, 'UNION') !== false or $id == false)
					return "1";
				$query = "SELECT * FROM lekarze WHERE id=".$id.";";
				return htmlentities($query, ENT_COMPAT, "UTF-8");
			}
			function queryDelPrep($id)
			{
				if (strpos($id, ';') !== false or strpos($id, '-') !== false or strpos($id, '=') or strpos($id, '%') !== false or strpos($id, 'union') !== false or strpos($id, 'UNION') !== false or $id == false)
					return "1";
				$query = "DELETE FROM lekarze WHERE id=".$id.";";
				return htmlentities($query, ENT_COMPAT, "UTF-8");
			}
			
			try
			{
				mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
				$mysqli = new mysqli("mysql.staszic.waw.pl","segir187","staszic187","segir187");
				if ($mysqli->connect_errno!=0)
				{
					echo "ERROR ".$mysqli->connect_errno;
				}
				$mysqli->set_charset('utf8mb4');
				
				echo "
					<br><br>
					<form method='post' action='delete_school.php'> 
						ID Lekarza: <input type='text' name='daneID' placeholder='Podaj ID lekarza'>
						<input type='submit' value='Usuń lekarza'>
					</form>
					<br>";
				
				$query = queryPrep($_REQUEST['daneID']);
				//echo $query;
				$queryRes = $mysqli->query($query);
				$queryRes = $queryRes->fetch_all(MYSQLI_ASSOC);
				if(is_null($queryRes[0])) echo "Błąd. Nie ma lekarza o ID".$_REQUEST['daneID'].".";
				else
				{
					echo "(".$queryRes[0]['id'].",'".$queryRes[0]['imie']."','".$queryRes[0]['nazwisko']."','".$queryRes[0]['specjalizacja']."','".$queryRes[0]['miasto']."','".$queryRes[0]['dni_pracy']."')";
					echo "<br><br>";
					$query = queryDelPrep($_REQUEST['daneID']);
					$queryRes2 = $mysqli->query($query);
					echo "Lekarza o ID".$_REQUEST['daneID']." usunięto pomyślnie.";
				}
				
			} catch (mysqli_sql_exception $e) {
				echo "Błąd. Niedozwolona wartość ID.";
			}
		?>
	</div>
	</div>
</body>
</html>
