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
    <title>Modyfikacja środków</title>
    <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/styl.css">
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
  
<?php
include ("naglowek.php");
if ((!isset($_GET['plan'])) || (!isset($_GET['rok'])) || (!isset($_GET['paragraf']))){
    header('Location: index.php');
    exit();
}
?>	

<h2>Aktualna wartość zaplanowana = <?php echo $_GET['plan'];?></h2>
<br />
<h2>Nowa planowana kwota dla paragrafu</h2>

<?php 
$rok = $_GET['rok'];
$id = $_GET['paragraf'];
$plan = $_GET['plan'];    
?>

<form  method="post" ><div>
Kwota zaplanowana:<br />
<input type="number" name="kwota_zaplanowana"><br />
Rok planu<br />
<?php
echo '<input type="number" name="wybrany_rok" value="' . $rok . '"><br /><br />';
?>
<input type="submit" value="Wyślij"><br />
<input type="reset" value="Wyczyść">
</form>

<?php 
if (isset($_POST['kwota_zaplanowana'])) {
    //echo "WYKONANIE IFA <br>";
    var_dump($_POST);
    $rok = $_POST['wybrany_rok'];
    $kwota_planu = $_POST['kwota_zaplanowana'];
    require_once ('baza.php');
    $polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach
    if ($polaczenie->connect_errno!=0) {
        echo "Błąd: " . $polaczenie->connect_errno;
    }else {
  if ($wynik = @$polaczenie->query(
	"select * FROM srodki where id_paragrafu in (select id from paragrafy where paragraf = (select paragraf from paragrafy where id = $id)) and rok = $rok
    ;"))
	{ 
$ile_paragrafow = $wynik->num_rows;
    echo "ILE PARAGRAFOW = " . $ile_paragrafow;
	if ($ile_paragrafow==0) {	
        $wstaw_srodki = @$polaczenie->query(
            "select id from paragrafy where paragraf = (select paragraf from paragrafy where id = 21)
    ;");  
        
    $licznik = 0;
    foreach($wstaw_srodki as $linia) {
    if ($licznik == 0) {
      $wstaw_nowe_srodki = @$polaczenie->multi_query(
      "insert into srodki (zaplanowane, id_paragrafu, rok) values (" . $kwota_planu . ", " . $linia['id'] . ", $rok)  
      ;");
      $licznik += 1;
    } else {
      $wstaw_nowe_srodki .= @$polaczenie->multi_query(
      "insert into srodki (zaplanowane, id_paragrafu, rok) values (" . $kwota_planu . ", " . $linia['id'] . ", $rok)  
      ;");
      $licznik += 1;
    }   
    }
	}else{
      $wynik = @$polaczenie->query(
      "UPDATE srodki SET zaplanowane = $kwota_planu  
      where id_paragrafu in (select id from paragrafy where paragraf = (select paragraf from paragrafy where id = $id)) and rok = $rok
    ;");        
    }
} //koniec ifa z zapytaniem 
    }

unset($_POST['kwota_zaplanowana']);
unset($_GET['rok']);
unset($_GET['paragraf']);
unset($_GET['plan']);
header('Location: budzet.php'); 
exit();
}

?> 
<br />
<?php include ("stopka.php"); ?>    