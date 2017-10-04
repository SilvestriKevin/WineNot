<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");


//creazione della pagina web
//leggo il file e lo stampo
echo file_get_contents("../html/wine.html");

?>
