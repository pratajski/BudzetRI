
<meta charset="utf-8">
<?php
session_start(); //odpalenie sesji
if (!isset($_POST['login']) || (!isset($_POST['haslo'])) ) {
header('Location: index.php');
exit();
}
require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else { //podłączyliśmy się do bazy
	$login = trim($_POST['login']) ;
	$haslo = trim($_POST['haslo']) ;
	$login = htmlentities($login, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
//	$haslo = htmlentities($haslo, ENT_QUOTES, "utf-8");//czyszczenie hasła ze znaków specjalnych

//wysłanie zapytania do bazy
if ($wynik = @$polaczenie->query(
	sprintf("select * FROM konto WHERE login='%s'", //%s oznacza stringa
		mysqli_real_escape_string($polaczenie,$login) //funkcja zabezpieczająca przed atakiem poprzez użyce -- w zapytniu SQL (komentarz)
				)))
	{
//sprawdzamy ile razy wystąpił wynik zapytania
	$ile_kont = $wynik->num_rows;
	if ($ile_kont==1) {
		$wiersz=$wynik->fetch_assoc();
			if (password_verify($haslo, $wiersz['konto_haslo'])) {
			$_SESSION['zalogowany'] = true;
			$_SESSION['id'] = $wiersz['id_konto'];
			$_SESSION['user'] = $wiersz['login']; //udostępnienie wyniku sesją
			unset($_SESSION['blad']);
			$wynik->free_result(); //czyszczenie zapytania SQL
			header('Location: lista.php'); //przekierowania do strony
			}//koniec ifa weryfikujacego hash konta
			else{
			$_SESSION['blad']='<span style="color:red">Nieprawidłowy login lub hasło</span>';
			header('Location: index.php');
			}
	} 						else{
	$_SESSION['blad']='<span style="color:red">Nieprawidłowy login lub hasło</span>';
	header('Location: index.php');
	}


}

	$polaczenie->close();
}

?>