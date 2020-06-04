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
	<title>Nowe zaangażowanie</title>
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
	Dostawca: <br />
<?php 
if (!isset($_POST['nazwa'])) {$_POST['nazwa'] = NULL;}
echo '<input type="text" name="nazwa"/><br />';
echo '<br />';

 ?>


Nr zaangażowania <br />
<?php
    echo '<input type="text" name="nr_zapotrzebowania"><br /><br />';
?> 

Opis: <br />
<?php 
    echo '<textarea name="opis" cols="20" rows="5"></textarea><br /><br />';
if (!isset($_POST['opis'])) {	
	$_POST['opis'] = NULL;    
//}else{echo '<textarea name="opis" cols="20" rows="5">Wpisz opis zaangażowania</textarea><br /><br />';
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
}else{
	echo '<input type="text" name="koniec" id="koniec"><br /><br />';
	echo '<br />';    
} ?> 

Kwota zaangażowania:<br />
<?php 
//if (!isset($_POST['kwota'])) {
	echo '<input type="text" name="kwota" value="0"><br /><br />';
//	$_POST['kwota'] = NULL;
//}else{echo '<<input type="number" name="kwota"><br /><br />';
//}
?> 
	<input type="reset" value="Skasuj" /> 
	<p></p>
	<input type="submit" value="Dodaj"/>
</form>
</div>

<?php
if (($_POST['start']) >  ($_POST['koniec'])   ) {
	echo "Sprawdź daty rozpoczęcia i zakończenia zaangażowania";
}
else
{

if (   (isset($_POST['nazwa']) and ($_POST['nazwa'] != NULL)) and (isset($_POST['start']) and ($_POST['start'] != NULL)) and (isset($_POST['koniec']) and ($_POST['koniec'] != NULL)) and (isset($_POST['kwota']) and ($_POST['kwota'] != NULL) and ($_POST['kwota'] >= 0)       )) { //sprawdzenie czy są wszystkie dane nowego zaangażowania
	
require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else { //podłączyliśmy się do bazy
	$nazwa = trim($_POST['nazwa']);
	$opis = trim($_POST['opis']);
    $nr_zaangazowania = trim($_POST['nr_zapotrzebowania']);
	$nazwa = htmlentities($nazwa, ENT_QUOTES, "utf-8");//czyszczenie nazwy ze znaków specjalnych
	$opis = htmlentities($opis, ENT_QUOTES, "utf-8");//czyszczenie opisu ze znaków specjalnych
    $nr_zaangazowania = htmlentities($nr_zaangazowania, ENT_QUOTES, "utf-8");//czyszczenie opisu ze znaków specjalnych
    $kwota = str_replace(",",".",$_POST['kwota']);
    if ($sql_zaangazowanie = @$polaczenie->query(
    sprintf("INSERT INTO zaangazowanie (nazwa, opis, poczatek, koniec, dodajacy_zaangazowanie, kwota, paragraf, zamowienie) 
    VALUES (" 
        . '"' . $nazwa . '", '
        . '"' . $opis . '", '
        . '"' . $_POST['start'] . '", '
        . '"' . $_POST['koniec'] . '", '
        . '"' . $_SESSION['id'] . '", '
        . '"' . $kwota . '", '
        . '"' . $_GET['paragraf'] . '", '
        . '"' . $nr_zaangazowania . '"'
    . ");")))
    {
        header('Location: lista.php');
        //echo 'dodano zaangażowanie';
    }
 //   $sql_zaangazowanie = ; 
}// koniec elsa z zapytaniem po podłączeniu do bazy

}//koniec ifa sprawdzającego czy są dane nowego zaangazowania
elseif  (($_POST['nazwa'] == NULL)or ($_POST["opis"]== NULL) or ($_POST["start"]== NULL) or ($_POST["koniec"]== NULL) ) {
		echo "Podaj wszystkie dane"; 
        
        
        
   

        
        
        
        
        
        
        
	}
elseif  ($_POST['kwota'] <= 0)    {echo "Kwota musi być większa od zera";}


}//koniec elsa sprawdzającego "start > koniec"

    
?>


<br />





<br />
<?php include ("stopka.php"); ?>