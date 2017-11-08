<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

// faccio i controlli sulla password inserita nei diversi campi:
// - che siano entrambe uguali e che sia quella giusta(controllo su database)
// - infine, se viene confermato, elimino dal database il vino


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/modify_wine.php.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[VINI]", $vini, $pagina);
mysqli_close($conn);
?>
