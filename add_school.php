<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UE-Compatible" content="IE=edge,chrome=1" />
	<title>Dodaj lekarza</title>
</head>
<body>
	<div id="main">
	<div id="top_banner">
		<h1>Dodaj lekarza</h1>
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
			
		?>
		
		<!-- wypisywanie danych -->
		
		<?php
			/*function prep()
			{
				$imiel=htmlentities($_REQUEST['imie'], ENT_COMPAT, "UTF-8");
				$nazwiskol=htmlentities($_REQUEST['nazwisko'], ENT_COMPAT, "UTF-8");
				$specjalizacjal=htmlentities($_REQUEST['specjalizacja'], ENT_COMPAT, "UTF-8");
				$miastol=htmlentities($_REQUEST['miasto'], ENT_COMPAT, "UTF-8");
				$dni_pracyl=htmlentities($_REQUEST['dni_pracy'], ENT_COMPAT, "UTF-8");
			}*/
			try
			{
				mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
				$mysqli = new mysqli("mysql.staszic.waw.pl","login","haslo","baza");
				if ($mysqli->connect_errno!=0)
				{
					echo "ERROR ".$mysqli->connect_errno;
				}
				$mysqli->set_charset('utf8mb4');
				$query = "SELECT id FROM lekarze ORDER BY id DESC LIMIT 1";
				$queryRes = $mysqli->query($query);
				$queryRes = $queryRes->fetch_all(MYSQLI_ASSOC);
				$newID = $queryRes[0]['id'] + 1;
				if($newID > 420)
				{
					echo "<br><br>
						BŁĄD. Zbyt wielu lekarzy w bazie. Usuń lekarza o najwyższym ID, aby móc dodać kolejnego.";
					return;
				}
				echo "
					<br><br>
					ID Nowego Lekarza: ".$newID."
					<br><br>
					Aby dodać lekarza, wpisz jego dane w poniższy formularz:<br><br>
					<form method='post' action='add_school.php'>
						Imię:  <input type='text' name='imieAdd' placeholder='Brak imienia'> <br>
						Nazwisko: <input type='text' name='nazwiskoAdd' placeholder='Brak nazwiska'> <br>
						Specjalizacja:  <input type='text' name='specjalizacjaAdd' placeholder='Brak specjalizacji'> <br>
						Miasto:  <input type='text' name='miastoAdd' placeholder='Brak miejscowości pracy'> <br>
						Dni Pracy (odzielone dwukropkiem):  <input type='text' name='dni_pracyAdd' placeholder='Brak dni pracy'> <br>
						<input type='submit' value='Dodaj lekarza'>
					</form><br>";
				
				if(!$_REQUEST['imieAdd'] OR !$_REQUEST['nazwiskoAdd'] OR !$_REQUEST['specjalizacjaAdd'] OR !$_REQUEST['miastoAdd'] OR !$_REQUEST['dni_pracyAdd'])
				{
					echo "ERROR. Zła wartość pola.";
				}
				else
				{
					$imied=htmlentities($_REQUEST['imieAdd'], ENT_COMPAT, "UTF-8");
					$nazwiskod=htmlentities($_REQUEST['nazwiskoAdd'], ENT_COMPAT, "UTF-8");
					$specjalizacjad=htmlentities($_REQUEST['specjalizacjaAdd'], ENT_COMPAT, "UTF-8");
					$miastod=htmlentities($_REQUEST['miastoAdd'], ENT_COMPAT, "UTF-8");
					$dni_pracyd=htmlentities($_REQUEST['dni_pracyAdd'], ENT_COMPAT, "UTF-8");
						$imiel=mysqli_real_escape_string($mysqli,$imied);
						$nazwiskol=mysqli_real_escape_string($mysqli,$nazwiskod);
						$specjalizacjal=mysqli_real_escape_string($mysqli,$specjalizacjad);
						$miastol=mysqli_real_escape_string($mysqli,$miastod);
						$dni_pracyl=mysqli_real_escape_string($mysqli,$dni_pracyd);
					
					$query = "INSERT INTO lekarze(id,imie,nazwisko,specjalizacja,miasto,dni_pracy) VALUES(".$newID.",'".$imiel."','".$nazwiskol."','".$specjalizacjal."','".$miastol."','".$dni_pracyl."');"; 
					$queryRes = $mysqli->query($query);
					echo "Dodano lekarza.";
					$query = "SELECT id FROM lekarze ORDER BY id DESC LIMIT 1";
					$queryRes = $mysqli->query($query);
					$queryRes = $queryRes->fetch_all(MYSQLI_ASSOC);
					$newID = $queryRes[0]['id'] + 1;
				}
			} catch (mysqli_sql_exception $e) {
				//echo $e->getMessage();
				echo "Błąd. Niepoprawnie wpisane wartości. Prosimy o ponowne poprawne wpisanie danych.";
			}
			?>
	</div>
	</div>
</body>
</html>
