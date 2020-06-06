<?php
session_start();

if (!isset($_SESSION['zalogowany'])) { //sprawdzenie czy ktoś jest zalogowany
	header('Location: index.php');
	exit();
}
if ((!isset($_GET['nr_zaangazowania'])) or (!isset($_GET['realizacja']))) { //sprawdzenie czy są zmienne potrzebne do dodania faktury
	header('Location: lista.php');
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Nowa faktura</title>
	<meta charset="utf-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" type="text/css" href="css/styl.css">
  <script>
  $( function() {
    $( "#wplyw" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
  } );
  </script>
    <script>
  $( function() {
    $( "#termin" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
  </script>
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
	echo '<input type="text" name="nazwa_dostawcy"/><br /><br />';
 ?>   
 
 	Tytułem: <br />
<?php 
	echo '<input type="text" name="tytul_platnosci"/><br /><br />';
 ?>  

Kwota faktury:<br />
<?php 
	echo '<input type="text" name="kwota_faktury" value="0"><br /><br />';
?> 

 	Nr faktury: <br />
<?php 
	echo '<input type="text" name="nr_faktury"/><br /><br />';
 ?>



Data wpływu:<br />
<?php 

if (!isset($_POST['data_wplywu'])) {
	echo '<input type="text" name="data_wplywu" id="wplyw"><br /><br />';
	$_POST['data_wplywu'] = NULL;
}else
	{echo '<input type="text" name="data_wplywu" id="wplyw"><br /><br />';
} ?> 
                                                                                                                                           
Termin płatności: <br />
<?php 
if (!isset($_POST['termin_platnosci'])) {
	echo '<input type="text" name="termin_platnosci" id="termin"><br /><br />';
	$_POST['termin_platnosci'] = NULL;   
}else{
	echo '<input type="text" name="termin_platnosci" id="termin"><br /><br />';   
} ?>                                       
                                                                                                                                                      
	Opis: <br />
<?php 
    echo '<textarea name="nowy_opis_faktury" cols="20" rows="5"></textarea><br /><br />';

?>
	<input type="reset" value="Skasuj" /> 
	<p></p>
	<input type="submit" value="Dodaj faturę"/>
</form>
</div>



<?php

if (   (isset($_POST['nazwa_dostawcy']) and ($_POST['nazwa_dostawcy'] != NULL)) 
    and (isset($_POST['kwota_faktury']) and ($_POST['kwota_faktury'] != NULL)) and ($_POST['kwota_faktury'] != 0)
    and (isset($_POST['tytul_platnosci']) and ($_POST['tytul_platnosci'] != NULL))
   ) { //sprawdzenie czy są wszystkie dane
	
require_once ('baza.php');

//lączenie z bazą
$polaczenie = new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach
//https://www.php.net/manual/en/mysqli.multi-query.php
if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else { //podłączyliśmy się do bazy
    $nr_zaangazowania = htmlentities($_GET['nr_zaangazowania'], ENT_QUOTES, "utf-8");//czyszczenie czesci url ze znaków specjalnych
    $zrealizowane = htmlentities($_GET['realizacja'], ENT_QUOTES, "utf-8");//czyszczenie czesci url ze znaków specjalnych
    $nr_faktury = $_POST['nr_faktury'];
	$nazwa = trim($_POST['nazwa_dostawcy']);
	$opis = trim($_POST['nowy_opis_faktury']);
    $tytul_platnosci = trim($_POST['tytul_platnosci']);
	$nazwa = htmlentities($nazwa, ENT_QUOTES, "utf-8");//czyszczenie logina ze znaków specjalnych
	$opis = htmlentities($opis, ENT_QUOTES, "utf-8");//czyszczenie hasła ze znaków specjalnych
    $tytul_platnosci = htmlentities($tytul_platnosci, ENT_QUOTES, "utf-8");//czyszczenie tytulu płatności ze znaków specjalnych
    $kwota = str_replace(",",".",$_POST['kwota_faktury']);
    $zrealizowane = $kwota +$zrealizowane;
    
    
$query  = "insert into zakupy (dostawca, tytul, kwota, numer, zaangazowanie, wplyw, termin, opis) values ('" 
    . $nazwa . "', '" 
    . $tytul_platnosci . "', '" 
    . $kwota . "', '"
    . $nr_faktury . "', '"
    . $nr_zaangazowania . "', '"
    . $_POST['data_wplywu'] . "', '"
    . $_POST['termin_platnosci'] . "', '"
    . $opis . "');";
$query .= "update zaangazowanie set realizacja = '$zrealizowane' where id_z='$nr_zaangazowania';";

    
$polaczenie->multi_query($query);    
    

$polaczenie->close();        
header('Location: lista.php');
exit();

}// koniec elsa z zapytaniem po podłączeniu do bazy

}//koniec ifa sprawdzającego czy są dane nowego projektu
else
{
    if ((isset($_POST['nazwa_dostawcy']) and ($_POST['nazwa_dostawcy'] != NULL))) {
            echo "Podaj nazwę dostawcy<br /><br />";
        }
                    
        if ((isset($_POST['kwota_faktury']) and ($_POST['kwota_faktury'] != NULL)) and ($_POST['kwota_faktury'] != 0)) {
            echo "Podaj kwotę faktury<br /><br />";
        }  
            
        if (isset($_POST['tytul_platnosci']) and ($_POST['tytul_platnosci'] != NULL)) {
            echo "Podaj tytuł płatności<br /><br />";
        }
}


?>


<br />

 



<br />
<?php include ("stopka.php"); ?>