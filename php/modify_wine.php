<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

$vini='';

//STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
$sql = "SELECT vini.* FROM vini";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $vini.="<li><div id='specific_result' class='specific_wine'><img alt='' src='../img/".$row['foto'].".png'/>";
        $vini.="<ul>";
        $vini.="<li><a title='Modifica vino' class='' href='./modify_wine.php' tabindex='' accesskey=''>Modifica</a></li>";
        $vini.="<li><a title='Elimina vino' class='' href='./delete_wine.php' tabindex='' accesskey=''>Elimina</a></li>";
        $vini.="<li><label>Nome: </label>".$row['nome']."</li>";
        $vini.="<li><label>Denominazione: </label>".$row['denominazione']."</li>";
        $vini.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
        $vini.="<li><label>Vitigno: </label>".$row['vitigno']."</li>";
        $vini.="<li><label>Annata: </label>".$row['annata']."</li>";
        $vini.="<li><label>Abbinamento: </label>".$row['abbinamento']."</li>";
        $vini.="<li><label>Degustazione: </label>".$row['degustazione']."</li>";
        $vini.="<li><label>Formato: </label>".$row['formato']."</li>";
        $vini.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
        $vini.="</ul></div></li>";
    }
else $vini.="<li><h2>Non sono presenti vini.</h2></li>";


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/modify_wine.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[VINI]", $vini, $pagina);
mysqli_close($conn);
?>
