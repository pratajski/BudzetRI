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
<input type="number" name="kwota_zaplanowana"><br />
		<input type="submit" value="Wyślij"><br />
		<input type="reset" value="Wyczyść">
</form>


<?php 
if (isset($_POST['kwota_zaplanowana'])) {
    echo "WYKONANIE IFA <br>";
    require_once ('baza.php');
    $kwota_planu = $_POST['kwota_zaplanowana'];
    $polaczenie = @new mysqli($host, $user, $password, $db);//próba podłączenia do bazy z ignorowaniem komunikatów o błędach
    $wynik = @$polaczenie->query(
      "UPDATE srodki SET zaplanowane = $kwota_planu  
      where id_paragrafu in (select id from paragrafy where paragraf = (select paragraf from paragrafy where id = $id)) and rok = $rok
    ;");  

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