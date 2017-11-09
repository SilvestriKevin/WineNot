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
        $vini.="<li>";
        $vini.="<ul class='wines_row'>";
        $vini.="<li>".$row['denominazione']."</li>";
        $vini.="<li>".$row['tipologia']."</li>";
        $vini.="<li>".$row['annata']."</li>";
        $vini.="<li class='modify_column'><a title='Modifica vino' class='' href='./modify_wine.php' tabindex='' accesskey=''>Modifica</a></li>";
        $vini.="<li class='remove_column'><a title='Elimina vino' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></li>";
        $vini.="</ul></li>";
    }
else $vini.="<li><h2>Non sono presenti vini.</h2></li>";


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[VINI]", $vini, $pagina);
mysqli_close($conn);
?>
