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
    <title>Zaangażowania</title>
    <meta charset="utf-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
<div style="naglowek">
<?php
include ("naglowek.php");

?>  
</div>
<form method="POST">

<table>
<tr>
    <td>Sposób sortowania</td><td><select name="wybor">
        <option value="start_r">Data rozpoczęcia - rosnąco</option>
        <option value="koniec_r">Data zakończenia - rosnąco</option>
        <option value="nazwa_r">Nazwa - rosnąco</option>
        <option value="start_m">Data rozpoczęcia - malejąco</option>
        <option value="koniec_m">Data zakończenia - malejąco</option>
        <option value="nazwa_m">Nazwa - malejąco</option>
        
</select></td>
</tr>

<tr>
    <td>Data początkowa:</td><td><input type="text" name="start" id="start"></td>
</tr>
<tr>
    <td>Data zakończenia:</td><td><input type="text" name="koniec" id="koniec"></td>
</tr>
<tr>
    <td>Paragraf:</td><td><input type="number" name="paragraf" id="paragraf"></td>
</tr>
<tr>
    <td></td><td><input type="submit" value="Zatwierdź wybór"></td>
</tr>
</table>

</form><br />

Lista zaangażowań:<br />
<div >
<table class="tabela">

<tr>
    <td>
    <h3>Nazwa</h3>
    </td>
    <td>
    <h3>Kwota zaangażowania</h3>
    </td>
    <td>
    <h3>Data rozpoczęcia</h3>
    </td>
    <td>
    <h3>Data zakończenia</h3>
    </td>
    <td>
    <h3>Zrealizowano</h3>
    </td>
    <td>
    <h3>Opis</h3>
    </td>
    <td>
   <h3>Paragraf</h3>
    </td>
    <td>
    <h3>Szczegóły</h3>
    </td>
</tr>

<?php
require_once ('baza.php');
if (isset($_POST['wybor'])) {
    $wybor = $_POST['wybor'];
}
else
{
    $wybor = 'nazwa';
}

$sortownia = 'nazwa'; //sortowanie po nazwie projektu
$kolejnosc = 'ASC'; // sortowanie rosnące

if ((!isset($_POST['start'])) or ($_POST['start'])=="") {
    $d_start = '2000-01-01';
}
else
{
    $d_start = $_POST['start'];
}

if ((!isset($_POST['koniec'])) or ($_POST['koniec'])=="") {
    $d_koniec = '2100-12-31';    
}
else
{
    $d_koniec = $_POST['koniec'];
}


if ($wybor == 'start_r'){
    $sortownia = 'poczatek'; //sortowanie po dacie rozpoczęcia projektu
    $kolejnosc = 'ASC'; // sortowanie rosnące
    
}
if ($wybor == 'koniec_r') {
    $sortownia = 'koniec'; //sortowanie po dacie zakończenia projektu
    $kolejnosc = 'ASC'; // sortowanie rosnące
}
if ($wybor == 'nazwa_r') {
    $sortownia = 'nazwa'; //sortowanie po nazwie projektu
    $kolejnosc = 'ASC'; // sortowanie rosnące
}
if ($wybor == 'start_m') {
    $sortownia = 'poczatek'; //sortowanie po dacie rozpoczęcia projektu
    $kolejnosc = 'DESC'; // sortowanie malejące
}
if ($wybor == 'koniec_m') {
    $sortownia = 'koniec'; //sortowanie po dacie zakończenia projektu
    $kolejnosc = 'DESC'; // sortowanie malejące
}
if ($wybor == 'nazwa_m') {
    $sortownia = 'nazwa'; //sortowanie po nazwie projektu
    $kolejnosc = 'DESC'; // sortowanie malejące
}
    
if (!isset($_POST['paragraf']) or ($_POST['paragraf'] == '')) {
    $numer_paragrafu = "%";
} else {
    $numer_paragrafu = trim($_POST['paragraf']) ;
	$numer_paragrafu = htmlentities($numer_paragrafu, ENT_QUOTES, "utf-8");//czyszczenie ze znaków specjalnych
}  

$polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach

$wynik = @$polaczenie->query(
    "SELECT * FROM zaangazowanie 
     LEFT JOIN paragrafy ON zaangazowanie.z_paragraf = paragrafy.id
    WHERE zaangazowanie.poczatek >= date('$d_start') AND zaangazowanie.koniec <= date('$d_koniec')  
    AND paragraf like \"$numer_paragrafu\"    
    order by $sortownia $kolejnosc"
    );

if(mysqli_num_rows($wynik) > 0) {
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */

foreach($wynik as $linia)

{
    echo "<tr>";
    echo '<td>';
    echo ($linia['nazwa_zaangazowania']);
    echo '</td>';
    echo '<td>';
    echo ($linia['kwota']);
    echo '</td>';
    echo '<td>';
    echo ($linia['poczatek']);
    echo '</td>';
    echo '<td>';
    echo ($linia['koniec']);
    echo '</td>';
    echo '<td>';
    echo ($linia['realizacja']);    
    echo '</td>';
    echo '<td>';
    echo ($linia['opis']);    
    echo '</td>';
    echo '<td>';
    echo $linia['dzial'] . "." . $linia['rozdzial'] .  "." . $linia['paragraf'] .  "." . $linia['punkt'];
    echo '</td>';
    echo '<td>';
    echo "<a href='"."faktury.php". "?nr_zaangazowania=" . $linia['id_z'] . "&realizacja=" . $linia['realizacja'] . "&plan=" . $linia['kwota'] . "'>Szczegóły</a>";
    echo '</td>';

echo "</tr>";

}
}

?>

</table></div>
<?php
$d_start = '2000-01-01';
$d_koniec = '2100-12-31';
?>

<br />

<!-- początek stopki -->
<br />
<?php include ("stopka.php"); ?>    
<!-- zakończenie stopki -->