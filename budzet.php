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
    <title>Budżet</title>
    <meta charset="utf-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#start" ).datepicker({ dateFormat: "yy",
});
    
  } );
  </script>
    <script>
  $( function() {
    $( "#koniec" ).datepicker({ dateFormat: 'yy' });
  } );
  </script>
<link rel="stylesheet" type="text/css" href="css/styl.css">
</head>
<body>
<div style="naglowek">
<?php
include ("naglowek.php");

?>  
</div>
<div id="container">
<form method="post">
	Rok: <br />

<?php
    $rok = date("Y");
    echo '<input type="number" name="rocznik" value="' . $rok . '"><br /><br />';
?> 

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
    
	<input type="reset" value="Skasuj" /> 
	<p></p>
	<input type="submit" value="Wybierz"/>
</form>
</div>



<?php
    if ((isset($_POST['rocznik'])) && (($_POST['rocznik']) != $rok)) {
    $rok = $_POST['rocznik'];
    }
    if ((isset($_POST['nowy_dzial_paragrafu'])) && ($_POST['nowy_dzial_paragrafu'] != "")) {
        $dzial = " and paragrafy.dzial = ";
        $dzial .= $_POST['nowy_dzial_paragrafu'];
    }
    if ((isset($_POST['nowy_rozdzial_paragrafu'])) && ($_POST['nowy_rozdzial_paragrafu'] != "")) {
        $rozdzial = " and paragrafy.rozdzial = ";
        $rozdzial .= $_POST['nowy_rozdzial_paragrafu'];
    }
    if ((isset($_POST['nowy_paragraf_paragrafu'])) && ($_POST['nowy_paragraf_paragrafu'] != "")) {
        $paragraf = " and paragrafy.paragraf = ";
        $paragraf .= $_POST['nowy_paragraf_paragrafu'];
    }  
    if ((isset($_POST['nowy_punkt_paragrafu'])) && ($_POST['nowy_punkt_paragrafu'] != "")) {
        $punkt = " and paragrafy.punkt = ";
        $punkt .= $_POST['nowy_punkt_paragrafu'];
    }  
    
    echo "<h1>Środki budżetowe zaplanowane na rok " . $rok . "</h1>";
    
   
    

require_once ('baza.php');



$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach
$wynik = @$polaczenie->query(
      "SELECT * FROM paragrafy LEFT JOIN srodki ON srodki.id_paragrafu = paragrafy.id   
      where srodki.rok = $rok 
      $dzial
      $rozdzial
      $paragraf
      $punkt
    "); 


if(mysqli_num_rows($wynik) == 0) {
    echo "Brak pozycji";
}   
    

if(mysqli_num_rows($wynik) > 0) {
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */
$suma_wydatkow = 0; 
   // if ($suma_wydatkow != 0) {
        
 //   }

echo '<table id="srodki_budzetowe">';

echo "<tr>";
    
    echo "<td>";
    echo "Nazwa";
    echo "</td>";
    echo "<td>";
    echo "Rok";
    echo "</td>";
    echo "<td>";
    echo "Paragraf";
    echo "</td>";
    echo "<td>";
    echo "Zaplanowano";
    echo "</td>";
    echo "<td>";
    echo "Wydano";
    echo "</td>";
    echo "<td>";
    echo "Zmień";
    echo "</td>";

echo "</tr>";

foreach($wynik as $linia)

{
    echo "<tr>";
    echo '<td>';
    echo $linia['nazwa'];
    echo '</td>';
    echo '<td>';
    echo $linia['rok'];
    echo '</td>';
    echo '<td>';
    echo $linia['dzial'] . "." . $linia['rozdzial'] .  "." . $linia['paragraf'] .  "." . $linia['punkt'];
    echo '</td>';
    echo '<td>';
    echo $linia['zaplanowane'];
    echo '</td>';
    echo '<td>';
    echo $linia['wydane'];
    $suma_wydatkow += $linia['wydane'];
    echo '</td>';
/*    echo '<td>';
    if (($linia['zaplanowane'] == NULL) or ($linia['wydane']) == NULL) {
        echo "";
    } else
    {
        echo $linia['zaplanowane'] - $linia['wydane']; 
    }       
    echo '</td>';*/
    echo '<td>';
        echo "<a href='"."modyfikacja_budzetu.php". "?paragraf=" . $linia['id_paragrafu'] . "&plan=" . $linia['zaplanowane'] . "&rok=" . $linia['rok'] . "'>Zmień</a>";

    echo '</td>';
    
echo "</tr>";


}
    echo "<h2>Łączna suma wydatków = " . $suma_wydatkow . "</h2>";
    echo "</table></div>"; 
}

?>


<?php 


 ?> 


<br />





<!-- początek stopki -->
<br />
<?php include ("stopka.php"); ?>    
<!-- zakończenie stopki -->