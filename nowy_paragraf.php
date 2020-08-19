<?php
session_start();

if (!isset($_SESSION['zalogowany'])) { //sprawdzenie czy ktoś jest zalogowany
	header('Location: index.php');
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Nowy paragraf</title>
	<meta charset="utf-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
	Nazwa nowego paragrafu: <br />
<?php 
if (!isset($_POST['nowa_nazwa_paragrafu']) or ($_POST['nowa_nazwa_paragrafu'] == NULL)) {
	echo '<input type="text" name="nowa_nazwa_paragrafu"/> Wpisz nazwę <br />';
	$_POST['nowa_nazwa_paragrafu'] = NULL;
	echo '<br />';
}else{echo '<input type="text" name="nowa_nazwa_paragrafu"/><br />';
echo '<br />';

} ?>   

Dział:<br />
<?php 
	echo '<input type="number" name="nowy_dzial_paragrafu" value="750"><br /><br />';
?> 

Rozdział:<br />
<?php 
	echo '<input type="number" name="nowy_rozdzial_paragrafu" value="75023"><br /><br />';
?> 
                                                                                              
Paragraf:<br />
<?php 
	echo '<input type="number" name="nowy_paragraf_paragrafu"><br /><br />';
?> 
                                                 
Punkt:<br />
<?php 
	echo '<input type="number" name="nowy_punkt_paragrafu"><br /><br />';
?>                                                                                                                                                   
                                                                                                                                                            
	Opis: <br />
<?php 
    echo '<textarea name="nowy_opis_paragrafu" cols="20" rows="5"></textarea><br /><br />';
if (!isset($_POST['nowy_opis_paragrafu'])) {
	$_POST['nowy_opis_paragrafu'] = "";
    }
?> 

 
	<input type="reset" value="Skasuj" /> 
	<p></p>
	<input type="submit" value="Dodaj paragraf"/>
</form>
</div>

<?php
$rok = date("Y");    
if (   (isset($_POST['nowa_nazwa_paragrafu']) and ($_POST['nowa_nazwa_paragrafu'] != NULL)) 
    and (isset($_POST['nowy_dzial_paragrafu']) and ($_POST['nowy_dzial_paragrafu'] != NULL)) 
    and (isset($_POST['nowy_rozdzial_paragrafu']) and ($_POST['nowy_rozdzial_paragrafu'] != NULL)) 
    and (isset($_POST['nowy_paragraf_paragrafu']) and ($_POST['nowy_paragraf_paragrafu'] != NULL)) 
    and (isset($_POST['nowy_punkt_paragrafu']) and ($_POST['nowy_punkt_paragrafu'] != NULL))    
   ) { //sprawdzenie czy są dane nowego paragrafu
	
require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else { //podłączyliśmy się do bazy
	$nazwa = trim($_POST['nowa_nazwa_paragrafu']);
	$opis = trim($_POST['nowy_opis_paragrafu']);
	$nazwa = htmlentities($nazwa, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
	$opis = htmlentities($opis, ENT_QUOTES, "utf-8");//czyszczenie hasła ze znaków specjalnych

   if ($wynik = @$polaczenie->query(
	sprintf("select * FROM paragrafy WHERE dzial=" . "'" . $_POST['nowy_dzial_paragrafu'] . "'" . " and rozdzial=" . "'" . $_POST['nowy_rozdzial_paragrafu'] . "'" 
             . " and paragraf=" . "'" . $_POST['nowy_paragraf_paragrafu'] . "'"
             . " and punkt=" . "'" . $_POST['nowy_punkt_paragrafu'] . "'" 
             . ";"                        
		)))
	{ //początek ifa z zapytaniem
//sprawdzenie czy istnieje już dodawany punkt
$ile_paragrafow = $wynik->num_rows;
	if ($ile_paragrafow==0) {		
    $sql_paragraf = "INSERT INTO paragrafy (nazwa, komentarz, dzial, rozdzial, paragraf, punkt, dodajacy_paragraf) VALUES (" 
    . "'" . $nazwa . "', " 	
    . "'".$opis . "', " 
    . "'" . $_POST['nowy_dzial_paragrafu'] . "', " 
    . "'" . $_POST['nowy_rozdzial_paragrafu'] . "', " 
    . "'" . $_POST['nowy_paragraf_paragrafu'] . "', " 
    . "'" . $_POST['nowy_punkt_paragrafu'] . "', " 
    . "'" . $_SESSION['user'] . "');";
    $sql_paragraf .= "INSERT INTO srodki (id_paragrafu, rok) values ((select id from paragrafy where "
    . "dzial = " . $_POST['nowy_dzial_paragrafu'] . " and " 
    . "rozdzial = " . $_POST['nowy_rozdzial_paragrafu'] . " and "  
    . "paragraf = " . $_POST['nowy_paragraf_paragrafu'] . " and " 
    . "punkt = " . $_POST['nowy_punkt_paragrafu'] . "), "
    . $rok . ");";

	if ($polaczenie->multi_query($sql_paragraf) === TRUE) {
	    echo "Pomyślnie dodano paragraf" . "<br />";
		} else 
		{
		echo "Dodanie projektu nieudane: " . $sql_paragraf->error;
		}
		
	}else
    {
        echo "Paragraf już istnieje";
        
    }
       
	} //koniec ifa z zapytaniem 
    

$polaczenie->close(); 

}// koniec elsa z zapytaniem po podłączeniu do bazy

}//koniec ifa sprawdzającego czy są dane nowego projektu
    

unset ($_POST['nowy_dzial_paragrafu']); 
unset ($_POST['nowy_rozdzial_paragrafu']);
unset ($_POST['nowy_paragraf_paragrafu']);
unset ($_POST['nowy_punkt_paragrafu']);
unset ($_POST['nowa_nazwa_paragrafu']);
unset ($_POST['nowy_opis_paragrafu']);
unset ($ile_paragrafow);
    
?>
<br />
<br />

<?php include ("stopka.php"); ?>