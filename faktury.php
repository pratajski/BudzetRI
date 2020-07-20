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
	<title>Lista faktur</title>
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
 <h1>Lista faktur z danego zaangażowania</h1>

 
 <?php

if (isset($_GET['nr_zaangazowania']))
{
    //$_SESSION['nr_zaangazowania']=$_GET['nr_zaangazowania'];
}
// Pierwszym krokiem jest sprawdzenie czy istnieje identyfikator zaangazowania, które będzie dalej obsługiwane. Jeżeli go nie ma to następuje powrót do listy zaangazowan i przerwanie dalszego wykonywania strony    
    if (isset($_GET['nr_zaangazowania'])){ //istnieje get nr zaangazowania, mozna wiec wyswietlic dane
        $zaangazowanie = $_GET['nr_zaangazowania'];    
    }else{//nie istnieje nr zaangazowania wiec wracamy do strony listą
        echo "na koniec prac puscic przejscie do wyboru zaangazowania gdy nie zostalo ono okreslone";
     //   header('Location: lista.php');
     //   exit();
    }

?>
 
 
<?php

require_once ('baza.php');

//lączenie z bazą
$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

if ($polaczenie->connect_errno!=0) {
echo "Błąd: " . $polaczenie->connect_errno;
}else { //podłączyliśmy się do bazy

	$sql_lista_faktur = @$polaczenie->query(
        "select * from zakupy where zaangazowanie='$zaangazowanie'"
    );
        
    
}//koniec ifa z zapytaniif(mysqli_num_rows($wynik) > 0) {
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */
if(mysqli_num_rows($sql_lista_faktur) > 0) {
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */
    

    echo "<div >";
    echo '<table id="lista_zaangazowan">';

    echo "<tr>";
        echo "<td>Dostawca</td>";
        echo "<td>Tytułem</td>";
        echo "<td>Kwota</td>";
        echo "<td>Nr faktury</td>";
        echo "<td>Data wpływu</td>";
        echo "<td>Termin płatności</td>";
        echo "<td>Opis</td>";
//        echo "<td>Szczegóły</td>";

    echo "</tr>";

$suma_wydatkow = 0;
 

foreach($sql_lista_faktur as $linia)

{
    echo "<tr>";
    
    echo '<td>';
    echo ($linia['dostawca']);
    echo '</td>';
    echo '<td>';
    echo ($linia['tytul']);
    echo '</td>';
    echo '<td>';
    echo ($linia['kwota']);
    
    echo '</td>';
    echo '<td>';
    echo ($linia['numer']);
    echo '</td>';
    echo '<td>';
    echo ($linia['wplyw']);
    echo '</td>';
    echo '<td>';
    echo ($linia['termin']);
    echo '</td>';
    echo '<td>';
    echo ($linia['opis']);
    echo '</td>';
    
    echo "</tr>";
$suma_wydatkow += $linia['kwota'];

}
}else{
    echo "<h3>Brak faktur</h3>";
}

echo "<h2>";
echo "Zaplanowano " . $_GET['plan'] . ", Wydano " . $suma_wydatkow;
echo "</h2>";

//echo "Pozostało " . $_GET['plan'] - $suma_wydatkow;
    
echo "</h2>";   

//  echo "<a href='"."faktury.php". "?nr_zaangazowania=" . $linia['id'] . "'>Szczegóły</a>";


    echo "</table>";
    echo "</div>";

echo "<br />";

if (isset($_GET['nr_zaangazowania'])) {
    echo "<button onclick=" . '"' . 'window.location.href=' . "'"   . '/nowa_faktura.php?nr_zaangazowania='. $_GET['nr_zaangazowania'] . '&realizacja=' . $_GET['realizacja'] . "'" . '"' .  '>Dodaj fakturę</button>';
}
    


?>

<br />
<?php include ("stopka.php"); ?>