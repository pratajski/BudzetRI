<?php
session_start();
//$_SESSION['user'] = "admin";
//puścić jak będzie działać
if (!isset($_SESSION['zalogowany'])) { //sprawdzenie czy ktoś jest zalogowany
	header('Location: index.php');
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Nowe zadanie</title>
	<meta charset="utf-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#start" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
  } );
  </script>
    <script>
  $( function() {
    $( "#koniec" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
  </script>
  <link rel="stylesheet" type="text/css" href="css/styl.css">
</head>
<body>
<?php

include ("naglowek.php");
?>	
<br />
 <br /><br />
<div id="container">
<form method="post">
	<!-- Nazwa projektu: <br /><input type="text" name="nazwa"/><br /> -->
	Nazwa nowego zadania: <br />
<?php 
if (!isset($_POST['nazwa']) or ($_POST['nazwa'] == NULL)) {
	echo '<input type="text" name="nazwa"/> Wpisz nazwę <br />';
	$_POST['nazwa'] = NULL;
	echo '<br />';
}else{echo '<input type="text" name="nazwa"/><br />';
echo '<br />';

} ?>                                                     
	Opis: <br />
<?php 
if (!isset($_POST['opis'])) {
	echo '<textarea name="opis" cols="20" rows="5">Wpisz opis zadania</textarea><br /><br />';
	$_POST['opis'] = NULL;
}else{echo '<textarea name="opis" cols="20" rows="5">Wpisz opis zadania</textarea><br /><br />';
}
?> 

Data początkowa: <br />
<?php 

if (!isset($_POST['start'])) {
	echo '<input type="text" name="start" id="start"><br /><br />';
	$_POST['start'] = NULL;
}else
	{echo '<input type="text" name="start" id="start"><br /><br />';
} ?> 

Data zakończenia: <br />
<?php 
if (!isset($_POST['koniec'])) {
	echo '<input type="text" name="koniec" id="koniec"><br /><br />';
	$_POST['koniec'] = NULL;
	echo '<br />';
}else
	{echo '<input type="text" name="koniec" id="koniec"><br /><br />';
	echo '<br />';
} ?> 
	<input type="reset" value="Skasuj" /> 
	<p></p>
	<input type="submit" value="Stwórz zadanie"/>
</form>
</div>



<?php
$zla_data = 0;
if (($_POST['start']) >  ($_POST['koniec'])   ) {
	echo "Sprawdź daty rozpoczęcia i zakończenia zadania";
}
else
{



if (   (isset($_POST['nazwa']) and ($_POST['nazwa'] != NULL)) and (isset($_POST['opis']) and ($_POST['opis'] != NULL)) and ($_POST['opis'] != "Wpisz opis zadania") and (isset($_POST['start']) and ($_POST['start'] != NULL)) and (isset($_POST['koniec']) and ($_POST['koniec'] != NULL))) { //sprawdzenie czy są dane nowego projektu
	
require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else { //podłączyliśmy się do bazy
	$nazwa = trim($_POST['nazwa']);
	$opis = trim($_POST['opis']);
	$nazwa = htmlentities($nazwa, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
	$opis = htmlentities($opis, ENT_QUOTES, "utf-8");//czyszczenie hasła ze znaków specjalnych



	$sql_projekt = "INSERT INTO projekt (projekt_nazwa, projekt_opis, projekt_start, projekt_koniec, projekt_szef) VALUES (" . "'" . $nazwa . "', " . "'".$opis . "', " . "'" . $_POST['start'] . "', " . "'" . $_POST['koniec'] . "', "  . "'" . $_SESSION['id'] . "')";

	if ($polaczenie->query($sql_projekt) === TRUE) {
	    echo "Pomyślnie dodano projekt" . "<br />";
		} else 
		{
		if ($_POST['opis'] == "Wpisz opis projektu") {
		echo " ";
		}
		else
		{
		echo "Dodanie projektu nieudane: " . $sql_projekt->error;
		}
		}





}// koniec elsa z zapytaniem po podłączeniu do bazy

}//koniec ifa sprawdzającego czy są dane nowego projektu
else
{
	if (($_POST['nazwa'] != NULL) or ($_POST["opis"]!= NULL) or ($_POST["start"]!= NULL) or ($_POST["koniec"]!= NULL)) {
		echo "Podaj wszystkie dane zadania"; 
	}
echo "<br/ >";
}


if (isset($sql_projekt)) { 
if ($nowy_pojekt = @$polaczenie->query(
	sprintf("select * FROM projekt WHERE projekt_nazwa='%s'", //%s oznacza stringa
		mysqli_real_escape_string($polaczenie,$nazwa) //funkcja zabezpieczająca przed atakiem poprzez użyce -- w zapytniu SQL (komentarz)
		)))
	{
//sprawdzamy ile razy wystąpił wynik zapytania
	$ile_projekt = $nowy_pojekt->num_rows;
	if ($ile_projekt==1) {
		$wiersz=$nowy_pojekt->fetch_assoc();
		$projekt['p_nazwa'] = $wiersz['projekt_nazwa'];
		$projekt['p_id'] = $wiersz['id_projekt']; //udostępnienie wyniku sesją
		$nowy_pojekt->free_result(); //czyszczenie zapytania SQL
		header('Location: lista.php');
	}

}



} // koniec ifa (isset($sql_projekt))


}//koniec elsa sprawdzającego "start > koniec"


?>


<br />





<br />
<?php include ("stopka.php"); ?>