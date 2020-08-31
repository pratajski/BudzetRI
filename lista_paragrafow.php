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
	<title>Nowe zaangażowanie</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/styl.css">

</head>
<body>
<div style="moje_dane">
<?php
include ("naglowek.php");
?>	
</div>

<br />
<h1>dodać sortowanie paragrafów</h1>
<br />
Wybierz paragraf dla zaangażowania:<br />

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
if ($wynik = @$polaczenie->query("select * from paragrafy"))
	{ //początek ifa z zapytaniem
    
if(mysqli_num_rows($wynik) > 0) {
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */
echo "<table id='tabela'>";
echo "<tr>";
    
    echo "<td>Nazwa paragrafu</td>";
    echo "<td>Dział</td>";
    echo "<td>Rozdział</td>";
    echo "<td>Paragraf</td>";
    echo "<td>Punkt</td>";
    echo "<td>Komentarz</td>";
    echo "<td>Wybierz</td>";
echo "</tr>";

foreach($wynik as $linia)
{    
 	echo "<tr>";    
    echo '<td>';
    echo ($linia['nazwa']);
    echo '</td>';
    
    echo '<td>';
    echo ($linia['dzial']);
    echo '</td>';    
    
    echo '<td>';
    echo ($linia['rozdzial']); 
    echo '</td>';    
    
    echo '<td>';
    echo ($linia['paragraf']);
    echo '</td>';    
    
    echo '<td>';
    echo ($linia['punkt']); 
    echo '</td>';    
    
    echo '<td>';
    echo ($linia['komentarz']); 
    echo '</td>';
    
    echo '<td>';
    echo "<a href='"."dodaj_zaangazowanie.php". "?paragraf=" . $linia['id'] . "'>Dodaj zaangażowanie</a>";; 
    echo '</td>';   
 
}
    
}else{
    // brak paragrafów
    echo "Dodaj paragrafy";
}

echo "</table>";
	$wynik->free_result(); //czyszczenie zapytania SQL
	} //koniec ifa z zapytaniem

	$polaczenie->close();
} //zamknięie elsa podłączenia do bazy

?>

<br />
<?php include ("stopka.php"); ?>