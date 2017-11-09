<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("./include/config.php");

//inclusione file per funzioni ausiliarie
include_once("./include/lib.php");

if(!empty($_SESSION['id']) && !empty($_GET['esci']) && $_GET['esci']==1){
    unset($_SESSION['id']);
    header('Location: index.php');
}


//creazione della pagina web
//leggo il file e lo stampo
echo file_get_contents("./html/index.html");
mysqli_close($conn);

?>
