<meta charset="utf-8">
<?php  
session_start();
if ((isset($_SESSION['zalogowany'])) &&  ($_SESSION['zalogowany']==true)) {
	header('Location: projekty.php');
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Rejestracja użytkownika</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/styl.css">
</head>
<body>
Wpisz dane rejestracyjne: <br />
<form action="rejestracja.php" method="post">

	Login: <br /><input type="text" name="nowy_login"/> 
<?php
if (!isset($_POST['nowy_login']) or ($_POST['nowy_login'] == NULL)) {
	echo "Podaj login";
	$koniec = 1;
}
?>

	<br />
	Hasło: <br /><input type="password" name="nowe_haslo"/>
<?php
$koniec = 0;
if (!isset($_POST['nowe_haslo']) or ($_POST['nowe_haslo']) == NULL) {
	echo "Wpisz hasło";
	$koniec = 1;
}
?>
	<br />

	Powtórz hasło: <br /><input type="password" name="nowe_haslo2"/>
<?php

if (!isset($_POST['nowe_haslo2']) or ($_POST['nowe_haslo2'] == NULL)) {
	echo "Powtórz hasło";
	$koniec = 1;
}
elseif (($_POST['nowe_haslo']) != ($_POST['nowe_haslo2'])) {
	echo "Różne hasła";
	$koniec = 1;
}

?>
	<br />
	<br />
	<input type="reset" value="Wyczyść" />
	<input type="submit" value="Zarejestruj się"/>
	
</form>

<?php
if ($koniec == 1) {
exit();
}

//===============================================


if (isset($_POST['nowy_login'])) {
	$login = trim($_POST['nowy_login']) ;
}

require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db); //próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else 
{
	
	$haslo = trim($_POST['nowe_haslo']) ;
	$login = htmlentities($login, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
//	$haslo = htmlentities($haslo, ENT_QUOTES, "utf-8");//czyszczenie hasła ze znaków specjalnych
	$haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);
//jesteśmy podłączeni do bazy

//wysłanie zapytania do bazy
	if ($wynik = @$polaczenie->query(
		sprintf("select * FROM konto WHERE login='%s'", //%s oznacza stringa
			mysqli_real_escape_string($polaczenie,$login) //funkcja zabezpieczająca przed atakiem poprzez użyce -- w zapytniu SQL (komentarz)
			)))	
	{
	//sprawdzamy ile razy wystąpił wynik
			$czy_konto = $wynik->num_rows;  
			if ($czy_konto==0) {
				$dodaj_konto = @$polaczenie->query("INSERT INTO konto (login, uprawnienia, konto_haslo) VALUES ('$login', '0' , '$haslo_hash')");
				$noweid = mysqli_insert_id($polaczenie);
				$dodaj_usera = @$polaczenie->query("INSERT INTO osoba (konto) VALUES ('$noweid')");
				$wynik->free_result($polaczenie); //czyszczenie zapytania SQL
				header('Location: index.php');
			}
			else
			{
		echo "Rejestracja niemożliwa - Login zajęty";
			}
		
			$polaczenie->close();
	}
}

?>

</body>
</html>
<?php  

?>
