<?php
session_start(); //sprawdzenie czy ktoś jest zalogowany
session_unset(); //skasowanie wszystkich zmiennych sesji
session_destroy();
header('Location: index.php');

?>
