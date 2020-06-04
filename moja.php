<?php  
session_start(); //puscic jak bedzie działać

if (!isset($_SESSION['zalogowany'])) { //sprawdzenie czy ktoś jest zalogowany
	header('Location: index.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Moje dane</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/styl.css">

</head>
<body>
<div style="moje_dane">
<?php
include ("naglowek.php");
?>	
</div>



<?php
$szkodnik = $_SESSION['user']; // konto -> login
$id = $_SESSION['id']; // konto -> id_konto

require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}
else { //podłączyliśmy się do bazy
	
// wysłanie zapytania do bazy
if ($wynik = @$polaczenie->query(
	sprintf("select * FROM konto WHERE login='%s'", //%s oznacza stringa
		mysqli_real_escape_string($polaczenie,$szkodnik) //funkcja zabezpieczająca przed atakiem poprzez użyce -- w zapytniu SQL (komentarz)
		)))
	{ //początek ifa z zapytaniem

$ile_kont = $wynik->num_rows;
	if ($ile_kont==1) {
		
	$linia=$wynik->fetch_assoc();
	
	$id_konto = $linia['id_konto'];
	$login = $linia['login'];
	$uprawnienia = $linia['uprawnienia'];
	$konto_haslo = $linia['konto_haslo'];
	$mail = $linia['mail'];
	$osoba = $linia['osoba'];	
	$wynik->free_result(); //czyszczenie zapytania SQL
		
	}
	} //koniec ifa z zapytaniem


if ($wynik2 = @$polaczenie->query(
	sprintf("select * FROM osoba WHERE konto='%s'", //%s oznacza stringa
		mysqli_real_escape_string($polaczenie,$id) //funkcja zabezpieczająca przed atakiem poprzez użyce -- w zapytniu SQL (komentarz)
		)))
	{ //początek zapytnia SQL


$ile_kont2 = $wynik2->num_rows;
	if ($ile_kont2==1) {
	
	$linia2=$wynik2->fetch_assoc();
	$id_osoba = $linia2['id_osoba'];
	$imie = $linia2['imie'];
	$nazwisko = $linia2['nazwisko'];
	$konto = $linia2['konto'];
	$o_mnie = $linia2['o_mnie'];	
	$wynik2->free_result(); //czyszczenie zapytania SQL
}
	}//koniec zapytnia SQL




	$polaczenie->close();
} //zamknięie elsa podłączenia do bazy
$nowy_login = "off";
$nowe_imie = "off";
$nowe_nazwisko = "off";
$nowy_mail = "off";
$nowe_omnie = "off";
$nowe_haslo = "off";


echo "<table id='tabela'>";
echo ' 	<tr>';
echo ' 		<td>Twój login: </td>	<td>' . $login . '</td>';
echo ' 	</tr>';
echo ' 	<tr>';
echo ' 		<td>Imię: </td>	<td>' . $imie . '</td>';
echo ' 	</tr>';
echo ' 	<tr>';
echo ' 		<td>Nazwisko: </td>	<td>' . $nazwisko . '</td>';
echo ' 	</tr>';
echo ' 	<tr>';
echo ' 		<td>Adres email: </td>	<td>' . $mail . ' </td>';
echo ' 	</tr>';
echo ' 	<tr>';
echo ' 		<td>Informacje o mnie: </td>	<td>' . $o_mnie . ' </td>';
echo ' 	</tr>';
echo "</table>";
echo "<br />";


//ify sprawdzające czy chce się zmienić dane
if (isset($_POST['nowy_login'])) {
$zmiana = "on";
$nowy_login = "on";	
}

if (isset($_POST['nowe_imie'])) {
$zmiana = "on";
$nowe_imie = "on";
}

if (isset($_POST['nowe_nazwisko'])) {
$zmiana = "on";
$nowe_nazwisko = "on";
}

if (isset($_POST['nowy_mail'])) {
$zmiana = "on";
$nowy_mail = "on";
}

if (isset($_POST['nowe_omnie'])) {
$zmiana = "on";
$nowe_omnie = "on";
}

if (isset($_POST['nowe_haslo'])) {
$zmiana = "on";
$nowe_haslo = "on";
}


if (isset($_POST['nowe_dane'])) {
		$nowe_dane = $_POST['nowe_dane'];
}

if (!isset($zmiana) or ($zmiana == "off")) {
echo '<div id="container2">';
echo ' <form method="post">';



echo ' <table id="prawy" >';

echo '<tr>';
echo '	<td>Chcę zmienić </td> <td><label>imię<input type="checkbox" name="nowe_imie" />	</label></td>';
echo '</tr>';

echo '<tr>';
echo '	<td>Chcę zmienić </td><td><label>nazwisko<input type="checkbox" name="nowe_nazwisko" /> </label>	</td>';
echo '</tr>';

echo ' 	<tr>';
echo ' 		<td>Chcę zmienić </td>	<td><label>mail<input type="checkbox" name="nowy_mail" /></label> </td>';
echo '</tr>';

echo ' 	<tr>';
echo ' <td>Chcę zmienić </td>	<td><label>mój opis<input type="checkbox" name="nowe_omnie" /></label></td>';
echo '</tr>';

echo ' 	<tr>';
echo ' <td>Chcę zmienić </td>	<td><label>hasło<input type="checkbox" name="nowe_haslo" /></label></td>';
echo '</tr>';

echo ' </table>';


echo '<br /><input type="reset" value="Skasuj wybór" /> <input type="submit" value="Chcę zmienić"/><br /><br />';
echo '<div id="container">';
} 
//jeżeli chcemy zmienić jakąś daną
elseif ($zmiana == "on") {
		echo '<form method="post">';
		echo '<table>';
		echo '<input type="hidden" name="nowe_dane" value="on" />';
	
		echo 'Wpisz jakie dane chcesz zmienić<br />';

	if ($nowy_login == "on") {
		
		echo '<tr>';
		echo '<td>Nowy login </td><td><input type="text" name="zm_login" /></td>';
		echo '</tr>';
	}
		
	if ($nowe_imie == "on") {
		echo '<tr>';
		echo '<td>Nowe imię </td><td><input type="text" name="zm_imie" /></td>';
		echo '</tr>';
	}
		
	if ($nowe_nazwisko == "on") {
		echo '<tr>';
		echo '<td>Nowe nazwisko </td><td><input type="text" name="zm_nazwisko" /></td>';
		echo '</tr>';
	}
		
	if ($nowy_mail == "on") {
		echo '<tr>';
		echo '<td>Nowy mail </td><td><input type="text" name="zm_mail" /></td>';
		echo '</tr>';
	}

	if ($nowe_haslo == "on") {
		echo '<tr>';
		echo '<td>Nowe hasło </td><td><input type="text" name="zm_haslo" /></td>';
		echo '</tr>';
	}

		
	if ($nowe_omnie == "on") {
		echo '<tr>';
		echo '<td>Nowe informacje o mnie </td><td><input type="text" name="zm_omnie" /></td>';
		echo '</tr>';
	}
		echo '';
		echo '';
		echo '</table>';
		
		echo '<br /><input type="reset" value="Skasuj" /> <p></p> <input type="submit" value="Zatwierdzam zmiany"/><br /><br />';
		echo '</form>';

		unset($zmiana);
		unset($nowy_login);
		unset($nowe_imie);
		unset($nowe_nazwisko);
		unset($nowy_mail);
		unset($nowe_omnie);
		unset($nowe_haslo);
		
} //koniec ifa $zmiana == "on"



$nowy_login = "off";
$nowe_imie = "off";
$nowe_nazwisko = "off";
$nowy_mail = "off";
$nowe_omnie = "off";
$nowe_haslo = "off";

if (isset($nowe_dane) and $nowe_dane == "on") {

$polaczenie_zm = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie_zm->connect_errno!=0) {
echo "Błąd: " . $polaczenie_zm->connect_errno;
exit();
}
else {
	//echo "połączenie z bazą";


echo '<br />';

	if (isset($_POST['zm_login'])) { //zapytanie SQL dla zmiany loginu - update
		$zm_login = $_POST['zm_login'];
		$zm_login = htmlentities($zm_login, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych

			$sql_zm_login = "UPDATE konto SET login='" . $zm_login . "' WHERE id_konto='$id'";

			if ($polaczenie_zm->query($sql_zm_login) === TRUE) {
			    echo "Pomyślnie zmieniono login" . "<br />";
			} else {
			    echo "Zmiana nieudana: " . $polaczenie_zm->error;
			}
		$_SESSION['user'] = $zm_login;
		$szkodnik = $_SESSION['user'];
		echo '<br />';
	}

	if (isset($_POST['zm_imie'])) { //zapytanie SQL dla zmiany imienia - update
		$zm_imie = $_POST['zm_imie'];
		$zm_imie = htmlentities($zm_imie, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych		
			$sql_zm_imie = "UPDATE osoba SET imie='" . $zm_imie . "' WHERE konto='$id'";

			if ($polaczenie_zm->query($sql_zm_imie) === TRUE) {
			    echo "Pomyślnie zmieniono imię" . "<br />";
			} else {
			    echo "Zmiana nieudana: " . $polaczenie_zm->error;
			}
	}

	if (isset($_POST['zm_nazwisko'])) { //zapytanie SQL dla zmiany nazwiska - update
		$zm_nazwisko = $_POST['zm_nazwisko'];
		$zm_nazwisko = htmlentities($zm_nazwisko, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
			$sql_zm_nazwisko = "UPDATE osoba SET nazwisko='" . $zm_nazwisko . "' WHERE konto='$id'";

			if ($polaczenie_zm->query($sql_zm_nazwisko) === TRUE) {
			    echo "Pomyślnie zmieniono nazwisko" . "<br />";
			} else {
			    echo "Zmiana nieudana: " . $polaczenie_zm->error;
			}
	}

	if (isset($_POST['zm_mail'])) { //zapytanie SQL dla zmiany maila - update
		$zm_mail = $_POST['zm_mail'];
		$zm_mail = htmlentities($zm_mail, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
			$sql_zm_mail = "UPDATE konto SET mail='" . $zm_mail . "' WHERE id_konto='$id'";

			if ($polaczenie_zm->query($sql_zm_mail) === TRUE) {
			    echo "Pomyślnie zmieniono adres e-mail" . "<br />";
			} else {
			    echo "Zmiana nieudana: " . $polaczenie_zm->error;
			}
	}

	if (isset($_POST['zm_omnie'])) { //zapytanie SQL dla zmiany info o mnie - update
		$zm_omnie = $_POST['zm_omnie'];
		$zm_omnie = htmlentities($zm_omnie, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
			$sql_zm_nazwisko = "UPDATE osoba SET o_mnie='" . $zm_omnie . "' WHERE konto='$id'";

			if ($polaczenie_zm->query($sql_zm_nazwisko) === TRUE) {
			    echo "Pomyślnie zmieniono opis" . "<br />";
			} else {
			    echo "Zmiana nieudana: " . $polaczenie_zm->error;
			}
	}


	if (isset($_POST['zm_haslo'])) { //zapytanie SQL dla zmiany info o mnie - update
		$zm_haslo = $_POST['zm_haslo'];
		$haslo_hash = password_hash($zm_haslo, PASSWORD_DEFAULT);
		// $haslo_hash = "222222";
		//$zm_haslo = htmlentities($zm_haslo, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
			$sql_zm_haslo = "UPDATE konto SET konto_haslo='" . $haslo_hash . "' WHERE id_konto='$id'";
			
			if ($polaczenie_zm->query($sql_zm_haslo) === TRUE) {
			    echo "Pomyślnie zmieniono hasło" . "<br />";
			} else {
			    echo "Zmiana nieudana: " . $polaczenie_zm->error;
			}
	}











	} //koniec if (isset($nowe_dane) and $nowe_dane == "on")





$polaczenie_zm->close();
header('Location: moja.php');


}//koniec wprowadzania nowych danych




//$wynik2->free_result(); //kasowanie zapytania





//===========================================================================
?>


<br />
<?php include ("stopka.php"); ?>	
<!-- zakończenie stopki -->
<!-- 
	<tr>
		<td><?php   ; ?></td>	<td><?php ; ?></td>	<td>Zmie</td>
	</tr>
	 -->