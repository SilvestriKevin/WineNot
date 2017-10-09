<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
echo file_get_contents("../html/admin_panel.html");
mysqli_close($conn);
?>
