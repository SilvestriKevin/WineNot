<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

$stampa='';
$immagine='';
$descrizione='';
$informazioni='';

if(!empty($_GET['id_wine'])){

    //STAMPA I DATI DEL VINO
    $sql = "SELECT vini.* FROM vini WHERE id_wine='".$_GET['id_wine']."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $immagine.="<img id='wine_img' alt='immagine del vino' src='../img/".$row['foto'].".png' />";

        $descrizione.=$row['descrizione'];

        $informazioni.="<li><label>Nome: </label>".$row['nome']."</li>";
        $informazioni.="<li><label>Denominazione: </label>".$row['denominazione']."</li>";
        $informazioni.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
        $informazioni.="<li><label>Vitigno: </label>".$row['vitigno']."</li>";
        $informazioni.="<li><label>Annata: </label>".$row['annata']."</li>";
        $informazioni.="<li><label>Abbinamento: </label>".$row['abbinamento']."</li>";
        $informazioni.="<li><label>Degustazione: </label>".$row['degustazione']."</li>";
        $informazioni.="<li><label>Formato: </label>".$row['formato']."</li>";
        $informazioni.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
    }
    else echo "<li><h2>Non sono presenti informazioni su questo vino. Torna alla <a title='home' href='../index.php' tabindex='' accesskey='h'>Home</a></h2></li>";
}
else {
    echo "<li><h2>Non ho capito a che vino ti stai riferendo. Torna alla <a title='home' href='../index.php' tabindex='' accesskey='h'>Home</a></h2></li>";
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
if(!empty($_SESSION['id'])) 
    $stampa = "<li><a title='Area Riservata' class='' href='../php/admin_panel.php' tabindex='' acceskey=''>Area Riservata</a></li>
               <li><a title='Esci dall'Area Riservata class='' href='../index.php?esci=1' tabindex='' accesskey='q'>Esci</a></li>";
else $stampa = "<li><a title='Area Riservata' class='' href='../php/login.php' tabindex='' accesskey=''>Area Riservata</a></li>";
$pagina = file_get_contents("../html/wine.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[IMMAGINE]", $immagine, $pagina);
$pagina = str_replace("[DESCRIZIONE]", $descrizione, $pagina);
$pagina = str_replace("[INFORMAZIONI]", $informazioni, $pagina);
echo str_replace("[AREA_RISERVATA]", $stampa, $pagina);
mysqli_close($conn);

?>
