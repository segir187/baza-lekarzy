<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UE-Compatible" content="IE=edge,chrome=1" />
	<title>Znajdź lekarzy</title>
</head>
<body>
	<div id="main">
	<div id="top_banner">
		<h1>Znajdź lekarzy</h1>
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
			function filtersPrep($filters)
			{
				$res = "";
				foreach ($filters as $name => $val)
				{
					$res = $res.$name.';'.$val.';';
				}
				return $res;
			}
			function filtersUnPrep($filters)
			{
				$res = array();
				$filtersArr = explode(';', $filters);
				$num = floor(count($filtersArr) / 2);
				for ($i = 0; $i < $num; $i++)
				{
					$res[$filtersArr[2 * $i]] = $filtersArr[2 * $i + 1];
				}
				return $res;
			}
			$filters = filtersUnPrep($_REQUEST["curFilters"]);
			if ($_REQUEST["newFilterName"] && $_REQUEST["newFilterVal"])
				$filters[$_REQUEST["newFilterName"]] = $_REQUEST["newFilterVal"];
			else if ($_REQUEST["newFilterName"])
				unset($filters[$_REQUEST["newFilterName"]]);
			if ($filters)
			{
				echo "
					Obecne filtry:<br>
					<table>
						<tr>
							<th>Pole</th>
							<th>Filtr</th>
						</tr>";
				foreach ($filters as $name => $val)
				{
					echo "
						<tr>
							<td>$name</td>
							<td>$val</td>
						</tr>";
				}
				echo "</table><br>";
			}
			echo "
				<br><br>
				Dodaj/zmień filtr: (pozostaw puste pole aby usunąć istniejący filtr)<br>
				<form method='post' action='display_school.php'>
					<input type='hidden' name='curFilters' value='" .filtersPrep($filters). "'>
					<input type='hidden' name='sortField' value='" .$_REQUEST['sortField']. "'>
					<input type='hidden' name='sortMethod' value='" .$_REQUEST['sortMethod']. "'>
					<select name='newFilterName'>
						<option value='id'>ID</option>
						<option value='imie'>Imię</option>
						<option value='nazwisko'>Nazwisko</option>
						<option value='specjalizacja'>Specjalizacja</option>
						<option value='miasto'>Miasto</option>
						<option value='dni_pracy'>Dni pracy</option>
					</select>
					<input type='text' name='newFilterVal' placeholder='Szukane'>
					<input type='submit' value='Dodaj filtr'>
				</form><br>";
			/*echo "
				Sortuj po:<br>
				<form method='post' action='display.php'>
					<input type='hidden' name='curFilters' value='" .filtersPrep($filters) "'>
					<select name='sortField'>
						<option value='id'>ID</option>
						<option value='imie'>Imię</option>
						<option value='nazwisko' selected>Nazwisko (domyślne)</option>
						<option value='specjalizacja'>Specjalizacja</option>
						<option value='miasto'>Miasto</option>
						<option value='dni_pracy'>Dni pracy</option>
					</select>
					<input type='radio' name='sortMethod' value='ASC' checked>Rosnąco
					<input type='radio' name='sortMethod' value='DESC'>Malejąco
					<input type='submit' value='Zastosuj'>
				</form><br>";*/
				echo "
					Sortuj po:<br>
					<form method='post' action=display_school.php>
						<input type='hidden' name='curFilters' value='" . filtersPrep($filters) . "'>
						<select name='sortField'>
							<option value='id'>ID</option>
							<option value='imie'>Imię</option>
							<option value='nazwisko' selected>Nazwisko</option>
							<option value='specjalizacja'>Specjalizacja</option>
							<option value='miasto'>Miasto</option>
							<option value='dni_pracy'>Dni pracy</option>
						</select>
						<input type='radio' name='sortMethod' value='ASC' checked>Rosnąco
						<input type='radio' name='sortMethod' value='DESC'>Malejąco
						<input type='submit' value='sort'>
					</form><br>";
		?>
		
		<!-- wypisywanie danych -->
		
		<?php
			function queryPrepFilters($filters)
			{
				if(!$filters) return false;
				$res = "WHERE ";
				$first = "true";
				foreach ($filters as $name => $val)
				{
					if ($val == "null" || $val == "not null")
						/*$res = $res."$name IS $val AND ";*/
					{
						if ($first=="true")
						{
							$first = "false";
							$res = $res."$name IS $val ";
						}
						else
							$res = $res."AND $name IS $val ";
					}
					else
						/*$res = $res."$name LIKE '$val' AND ";*/
					{
						if (strpos($val, ';') !== false or strpos($val, '=') !== false)
							continue;
						if ($first=="true")
						{
							$first = "false";
							$res = $res."$name LIKE '$val' ";
						}
						else
							$res = $res."AND $name LIKE '$val' ";
					}
				}
				return htmlentities($res, ENT_COMPAT, "UTF-8");
				/*return $res;*/
			}
			function queryPrepSort()
			{
				if(!$_REQUEST['sortField'])
					return "ORDER BY id ASC";
				return "ORDER BY ".$_REQUEST['sortField']." ".$_REQUEST['sortMethod'];
			}
			function queryPrep($filters)
			{
				$query = "SELECT * FROM lekarze ";
				$queryFilters = queryPrepFilters($filters);
				if ($queryFilters)
					$query = $query." ".$queryFilters;
				$querySort = queryPrepSort();
				if ($querySort)
					$query = $query." ".$querySort;
				return $query;
			}
			try
			{
				mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
				$mysqli = new mysqli("mysql.staszic.waw.pl","login","haslo","baza");
				if ($mysqli->connect_errno!=0)
				{
					echo "ERROR ".$mysqli->connect_errno;
				}
				else
				{
					$mysqli->set_charset('utf8mb4');
					$query = queryPrep($filters);
					/*echo "$query<br>";*/
					$queryRes = $mysqli->query($query);
					if($queryRes)
						$queryRes = $queryRes->fetch_all(MYSQLI_ASSOC);
					echo "<table>";
					echo "
						<tr>
							<th>ID</th>
							<th>Imię</th>
							<th>Nazwisko</th>
							<th>Specjalizacja</th>
							<th>Miasto</th>
							<th>Dni pracy</th>
						</tr>";
					if ($queryRes)
					{
						foreach ($queryRes as $row)
						{
							echo "
								<tr>
									<td>".$row['id']."</td>
									<td>".$row['imie']."</td>
									<td>".$row['nazwisko']."</td>
									<td>".$row['specjalizacja']."</td>
									<td>".$row['miasto']."</td>
									<td>".$row['dni_pracy']."</td>
								</tr>";
						}
					}
					echo "</table>";
				}
			} catch (mysqli_sql_exception $e) {
				echo "Błąd. Niedozwolona wartość filtru. Prosimy o przejście na stronę główną.";
			}
		?>
	</div>
	</div>
</body>
</html>
