<meta charset="utf-8">
<?php //testowe - plik do skasowania lub przeredagowania
if (!isset($_POST['login']) || (!isset($_POST['haslo'])) ) {
echo "Brak loginu i hasła";
die();
}


require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db); //próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else {
	$login = $_POST['login'];
	$haslo = $_POST['haslo'];
//jesteśmy podłączeni do bazy
$sql = "select * FROM konto WHERE login='$login' and konto_haslo='$haslo'"; //wysłanie zapytania do bazy
//sprawdzamy ile razy wystąpił wynik
if ($wynik = @$polaczenie->query($sql)){
	$ile_kont = $wynik->num_rows;
	if ($ile_kont==1) {
		$wiersz=$wynik->fetch_assoc();
		$user = $wiersz['login'];



		$wynik->free_result(); //czyszczenie zapytania SQL
		header('Location');
	}else{
echo "Logowanie nieudane";
	}


}





	$polaczenie->close();
}

?> 
