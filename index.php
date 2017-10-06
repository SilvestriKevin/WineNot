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
if(!empty($_SESSION['id'])) 
    $stampa = "<li><a title='Area Riservata' class='' href='./php/admin_panel.php' tabindex='' accesskey=''>Area Riservata</a></li>
               <li><a title='Esci dall'Area Riservata class='' href='./index.php?esci=1' tabindex='' accesskey='q'>Esci</a></li>";
else $stampa = "<li><a title='Area Riservata' class='' href='./php/login.php' tabindex='' accesskey=''>Area Riservata</a></li>";
$pagina = file_get_contents("./html/index.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[AREA_RISERVATA]", $stampa, $pagina);
mysqli_close($conn);

?>
