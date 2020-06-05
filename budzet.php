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
	Zmień rok: <br />

<?php
    $rok = date("Y");
    echo '<input type="number" name="rocznik" value="' . $rok . '"><br /><br />';
?> 
	<input type="reset" value="Skasuj" /> 
	<p></p>
	<input type="submit" value="Wybierz"/>
</form>
</div>

<br />

<?php
    if (isset ($_POST['rocznik'])) {
    $rok = $_POST['rocznik'];
}
    echo "<h1>Środki budżetowe na rok " . $rok . "</h1>";
?>




<?php
require_once ('baza.php');


$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach
$wynik = @$polaczenie->query(
      "SELECT * FROM paragrafy LEFT JOIN srodki ON srodki.id_paragrafu = paragrafy.id   
      where srodki.rok = $rok or srodki.rok is NULL
    "); 

if(mysqli_num_rows($wynik) == 0) {
    echo "Brak pozycji";
}   
    

if(mysqli_num_rows($wynik) > 0) {
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */
    
echo '<table id="srodki_budzetowe">';

echo "<tr>";
    
    echo "<td>";
    echo "Nazwa";
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
    echo "Pozostało";
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
    echo $linia['dzial'] .  "." . $linia['dzial'] . "." . $linia['rozdzial'] .  "." . $linia['paragraf'] .  "." . $linia['punkt'];
    echo '</td>';
    echo '<td>';
    echo $linia['zaplanowane'];
    echo '</td>';
    echo '<td>';
    echo $linia['wydane'];
    echo '</td>';
    echo '<td>';
    if (($linia['zaplanowane'] == NULL) or ($linia['wydane']) == NULL) {
        echo "";
    } else
    {
        echo $linia['zaplanowane'] - $linia['wydane']; 
    }       
    echo '</td>';
    echo '<td>';
        echo "<a href='"."modyfikacja_budzetu.php". "?paragraf=" . $linia['id_paragrafu'] . "&plan=" . $linia['zaplanowane'] . "'>Zmień</a>";

    echo '</td>';
    
echo "</tr>";


}
}

?>

</table></div>
<?php 


 ?> 


<br />





<!-- początek stopki -->
<br />
<?php include ("stopka.php"); ?>    
<!-- zakończenie stopki -->