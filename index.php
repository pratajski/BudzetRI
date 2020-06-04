<?php
session_start(); //sprawdzenie czy ktoś jest zalogowany
if (  (isset($_SESSION['zalogowany'])) &&  ($_SESSION['zalogowany']==true)      ) {
	header('Location: lista.php');
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Strona główna</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/styl.css">
</head>
<body>
<div id="okolo_container">
<br /></div>
<div id="container">
<form action="logowanie.php" method="post">
	Login: <br /><input type="text" name="login"/><br />
	Hasło: <br /><input type="password" name="haslo"/><br /><br />
	<!-- <input type="reset" value="Skasuj" /> -->
	<input type="submit" value="Zaloguj się"/>
</form></div>
<div id="okolo_container">
Jeżeli nie masz konta zarejestruj się: <br />
<a href="rejestracja.php">Rejestracja</a> 
<?php
if (isset($_SESSION['blad'])) {
	echo "<br />";
	echo $_SESSION['blad'];
}

?>
</div>

</body>
</html>
<?php

?>