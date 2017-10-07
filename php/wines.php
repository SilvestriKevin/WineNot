<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

$stampa='';
$vini='';
$annata='';
$tipologia='';

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

//SELECT TIPOLOGIA NEL FORM
$sql = "SELECT tipologia FROM vini GROUP BY tipologia ORDER BY tipologia";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $tipologia.="<option>".$row['tipologia']."</option>";
    }

//SELECT ANNATA NEL FORM
$sql = "SELECT annata FROM vini GROUP BY annata ORDER BY annata";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $annata.="<option>".$row['annata']."</option>";
    }

//STAMPA I VINI DELL'ANNATA
$sql = "SELECT vini.* FROM vini";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $vini.="<li><div id='specific_result' class='specific_wine'><img alt='' src='../img/merlot.png'/><ul>";
        $vini.="<li><label>Nome: </label>".$row['nome']."</li>";
        $vini.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
        $vini.="<li><label>Vitigno: </label>".$row['vitigno']."</li>";
        $vini.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
        $vini.="</ul></div></li>";
    }
else $vini.="<li><h2>Non sono presenti vini per questa annata.</h2></li>";


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
if(!empty($_SESSION['id'])) 
    $stampa = "<li><a title='Area Riservata' class='' href='../php/admin_panel.php' tabindex='' acceskey=''>Area Riservata</a></li>
               <li><a title='Esci dall'Area Riservata class='' href='../index.php?esci=1' tabindex='' accesskey='q'>Esci</a></li>";
else $stampa = "<li><a title='Area Riservata' class='' href='../php/login.php' tabindex='' accesskey=''>Area Riservata</a></li>";
$pagina = file_get_contents("../html/wines.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[ANNATA]", $annata, $pagina);
$pagina = str_replace("[TIPOLOGIA]", $tipologia, $pagina);
$pagina = str_replace("[VINI]", $vini, $pagina);
echo str_replace("[AREA_RISERVATA]", $stampa, $pagina);
mysqli_close($conn);
?>
