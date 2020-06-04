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
	<title>Budżet</title>
	<meta charset="utf-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link rel="stylesheet" type="text/css" href="css/styl.css">
</head>
<body>
<?php

include ("naglowek.php");
?>	
<br />
 <br /><br />
 <h1>Lista pozostałych na każdym paragrafie środków</h1>
<div id="container">




<?php

if (($_POST['start']) >  ($_POST['koniec'])   ) {
	echo "Sprawdź daty rozpoczęcia i zakończenia zaangażowania";
}
else
{



if (   (isset($_POST['nazwa']) and ($_POST['nazwa'] != NULL)) and ($_POST['opis'] != "Wpisz opis zaangażowania") and (isset($_POST['start']) and ($_POST['start'] != NULL)) and (isset($_POST['koniec']) and ($_POST['koniec'] != NULL)) and (isset($_POST['kwota']) and ($_POST['kwota'] != NULL))) { //sprawdzenie czy są wszystkie dane nowego zaangażowania
	
require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else { //podłączyliśmy się do bazy
	$nazwa = trim($_POST['nazwa']);
	$opis = trim($_POST['opis']);
	$nazwa = htmlentities($nazwa, ENT_QUOTES, "utf-8");//czyszczenie nazwy ze znaków specjalnych
	$opis = htmlentities($opis, ENT_QUOTES, "utf-8");//czyszczenie opisu ze znaków specjalnych



	$sql_projekt = "INSERT INTO zaangazowanie (nazwa, opis, poczatek, koniec, projekt_szef) VALUES (" . "'" . $nazwa . "', " . "'".$opis . "', " . "'" . $_POST['start'] . "', " . "'" . $_POST['koniec'] . "', "  . "'" . $_SESSION['id'] . "')";

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