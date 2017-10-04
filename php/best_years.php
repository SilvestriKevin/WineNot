<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

$stampa='';
$vini='';
$annate='';
$description_annata='';

/*
if(!empty($_COOKIE['error'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['error']."</h1><br></br>";
    setcookie('error',null);
}
if(!empty($_COOKIE['info'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['info']."</h1><br></br>";
    setcookie('info',null);
}
*/

$cont = 6;

//STAMPA LE ANNATE
$sql = "SELECT annate.* FROM annate WHERE migliore=1";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0) 
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $annate.="<li><a title='".$row['anno']."' class='' href='./best_years.php?year=".$row['anno']."' tabindex='".$cont."'>".$row['anno']."</a></li>";
        $cont++;
    }
else  $annate.="<li><h2>Non sono presenti annate.</h2></li>";

//STAMPA LE INFORMAZIONI DELL'ANNATA
$sql = "SELECT annate.* FROM annate WHERE anno=";
if(!empty($_GET['year'])) $sql.="'".$_GET['year']."' AND migliore=1";
else $sql.="(SELECT MAX(anno) FROM annate WHERE migliore=1)";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0){
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $description_annata.="<li><label>Anno: </label>".$row['anno']."</li>";
    $description_annata.="<li><label>Descrizione: </label>".$row['descrizione']."</li>";
    $description_annata.="<li><label>Qualit&agrave;: </label>".$row['qualita']."</li>";

    //STAMPA I VINI DELL'ANNATA
    $sql = "SELECT vini.* FROM vini WHERE annata='".$row['anno']."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vini.="<li><div><img alt='' src=''/><ul>";
            $vini.="<li><label>Nome: </label>".$row['nome']."</li>";
            $vini.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
            $vini.="<li><label>Vitigno: </label>".$row['vitigno']."</li>";
            $vini.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
            $vini.="</ul></div></li>";
        }
    else $vini.="<li><h2>Non sono presenti vini per questa annata.</h2></li>";
}
//se viene manomesso l'URL e l'anno inserito non è tra le annate migliori
else if(!empty($_GET['year']))$description_annata.="<li><h2>L'annata selezionata non risulta tra le migliori.</h2></li>";


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
if(!empty($_SESSION['id'])) 
    $stampa = "<li><a title='Area Riservata' class='' href='../php/admin_panel.php' tabindex='' acceskey=''>Area Riservata</a></li>
               <li><a title='Esci dall'Area Riservata class='' href='../index.php?esci=1' tabindex='' accesskey='q'>Esci</a></li>";
else $stampa = "<li><a title='Area Riservata' class='' href='../php/login.php' tabindex='' acceskey=''>Area Riservata</a></li>";
$pagina = file_get_contents("../html/best_years.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[ANNATE]", $annate, $pagina);
$pagina = str_replace("[DESCRIZIONE_ANNATA]", $description_annata, $pagina);
$pagina = str_replace("[VINI_ANNATA]", $vini, $pagina);
echo str_replace("[AREA_RISERVATA]", $stampa, $pagina);
mysqli_close($conn);
?>
