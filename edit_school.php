<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UE-Compatible" content="IE=edge,chrome=1" />
	<title>Edytuj dane lekarza</title>
</head>
<body>
	<div id="main">
	<div id="top_banner">
		<h1>Edytuj dane lekarza</h1>
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
				$query = "SELECT * FROM lekarze WHERE id=";
				if(strpos($id, ';') !== false or strpos($id, '-') !== false)
					return $query;
				if($id == false) $id = "0";
				$query = $query.$id.";";
				return htmlentities($query, ENT_COMPAT, "UTF-8");
			}
			function querySavePrep($id,$imie,$nazw,$spec,$mias,$dni)
			{
				if (strpos($id, ';') !== false or strpos($id, '-') !== false or strpos($id, '=') or strpos($id, '%') !== false or strpos($id, 'union') !== false or strpos($id, 'UNION') !== false)
					return "1";
				if(strpos($imie, ';') !== false or strpos($imie, '-') !== false or strpos($imie, '=') or $imie == false or strpos($imie, '%') !== false or strpos($imie, 'union') !== false or strpos($imie, 'UNION') !== false)
					return "2";
				if(strpos($nazw, ';') !== false or strpos($nazw, '-') !== false or strpos($nazw, '=') or $nazw == false or strpos($nazw, '%') !== false or strpos($nazw, 'union') !== false or strpos($nazw, 'UNION') !== false)
					return "3";
				if(strpos($spec, ';') !== false or strpos($spec, '-') !== false or strpos($spec, '=') or $spec == false  or strpos($spec, '%') !== false or strpos($spec, 'union') !== false or strpos($spec, 'UNION') !== false)
					return "4";
				if(strpos($mias, ';') !== false or strpos($mias, '-') !== false or strpos($mias, '=') or $mias == false or strpos($mias, '%') !== false or strpos($mias, 'union') !== false or strpos($mias, 'UNION') !== false)
					return "5";
				if(strpos($dni, ';') !== false or strpos($dni, '-') !== false or strpos($dni, '=') or $dni == false or strpos($dni, '%') !== false or strpos($dni, 'union') !== false or strpos($dni, 'UNION') !== false)
					return "6";
				if($id == false) 
					return "1";
				$query = "UPDATE lekarze SET imie='".$imie."', nazwisko='".$nazw."', specjalizacja='".$spec."',
						  miasto='".$mias."', dni_pracy='".$dni."' WHERE id=".$id.";";
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
					<br> </br>
					<form method='post' action='edit_school.php'> 
						ID Lekarza: <input type='text' name='daneID' placeholder='Podaj ID lekarza' value=".$_REQUEST['daneID'].">
						<input type='submit' value='Szukaj'>
					</form>";
					
				$query = queryPrep($_REQUEST['daneID']);
				$queryRes = $mysqli->query($query);
				if($queryRes) 
				{
					$queryRes = $queryRes->fetch_all(MYSQLI_ASSOC);
					echo "<br>Wyświetlam dane lekarza z ID".$_REQUEST['daneID'].":";
				}
				else echo "<br>Nie ma takiego lekarza.";
				
				echo "
					<br> <br>
					<form method='post' action='edit_school.php'>
						<input type='hidden' name='noweID' value=".$_REQUEST['daneID'].">
						Imię: <input type='text' name='noweImie' placeholder='Brak imienia' value='".$queryRes[0]['imie']."'> <br> 
						Nazwisko: <input type='text' name='noweNazw' placeholder='Brak nazwiska' value='".$queryRes[0]['nazwisko']."'> <br>
						Specjalizacja: <input type='text' name='nowaSpec' placeholder='Brak specjalizacji' value='".$queryRes[0]['specjalizacja']."'> <br>
						Miasto: <input type='text' name='noweMias' placeholder='Brak miejscowości pracy' value='".$queryRes[0]['miasto']."'> <br>
						Dni Pracy (odzielone dwukropkiem): <input type='text' name='noweDni' placeholder='Brak dni pracy' value='".$queryRes[0]['dni_pracy']."'> <br> <br>
						<input type='submit' value='Zapisz zmiany'>
					</form>
					<br>";
				
				$query = querySavePrep($_REQUEST['noweID'],$_REQUEST['noweImie'],$_REQUEST['noweNazw']
						,$_REQUEST['nowaSpec'],$_REQUEST['noweMias'],$_REQUEST['noweDni']);
				//echo $query." ";
				if($query == "1") echo "Aktualizacja nie powiodła się: Błędne ID lekarza.";
				else if($query == "2") echo "Aktualizacja nie powiodła się: Błędne imię lekarza.";
				else if($query == "3") echo "Aktualizacja nie powiodła się: Błędne nazwisko lekarza.";
				else if($query == "4") echo "Aktualizacja nie powiodła się: Błędna specjalizacja lekarza.";
				else if($query == "5") echo "Aktualizacja nie powiodła się: Błędna miejscowość pracy lekarza.";
				else if($query == "6") echo "Aktualizacja nie powiodła się: Błędne dni pracy lekarza.";
				else
				{
					$queryRes = $mysqli->query($query);
					//echo $queryRes." ";
					if($queryRes == false) echo "Aktualizacja nie powiodła się.";
					else echo "Dane lekarza zaaktualizowano pomyślnie.";
				}
				
			} catch (mysqli_sql_exception $e) {
				echo "Błąd. Niedozwolona wartość filtru.";
			}
		?>
	</div>
	</div>
</body>
</html>
