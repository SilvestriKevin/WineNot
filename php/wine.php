<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

$immagine='';
$descrizione='';
$informazioni='';

//in base a che cookie è settato, assegno alla variabile $indietro l'indirizzo che diverrà l'href del pulsante 'torna indietro'
if(isset($_COOKIE['indietro'])){
    $indietro=$_COOKIE['indietro'];
    unset($_COOKIE['indietro']);
    setcookie('indietro', '', time() - 3600);
}
//se ricado in questo caso significa che l'utente ha scritto direttamente l'url della pagina del vino specifico
//es.http://localhost/WineNot/php/wine.php?id_wine=3 senza passare per la pagina dei vini o delle annate
else $indietro='../index.html';

if(!empty($_GET['id_wine'])){

    //STAMPA I DATI DEL VINO
    $sql = "SELECT vini.* FROM vini WHERE id_wine='".$_GET['id_wine']."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $immagine.="<img id='wine_img' alt='immagine del vino' src='../img/".$row['id_wine'].".png' />";

        $descrizione.=$row['descrizione'];

        $informazioni.="<li class='title_details'><label>Dettagli: </label>"."</li>";

        $informazioni.="<li><label>Nome: </label>".$row['nome']."</li>";
        $informazioni.="<li><label>Denominazione: </label>".$row['denominazione']."</li>";
        $informazioni.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
        $informazioni.="<li><label>Vitigno: </label>".$row['vitigno']."</li>";
        $informazioni.="<li><label>Annata: </label>".$row['annata']."</li>";

        $informazioni.="<li class='title_details'><label>Piatti e Occasioni: </label>"."</li>";

        $informazioni.="<li><label>Abbinamento: </label>".$row['abbinamento']."</li>";
        $informazioni.="<li><label>Degustazione: </label>".$row['degustazione']."</li>";

        $informazioni.="<li class='title_details'><label>Quantità: </label>"."</li>";

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
$pagina = file_get_contents("../html/wine.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[IMMAGINE]", $immagine, $pagina);
$pagina = str_replace("[DESCRIZIONE]", $descrizione, $pagina);
$pagina = str_replace("[INDIETRO]", $indietro, $pagina);
echo str_replace("[INFORMAZIONI]", $informazioni, $pagina);
mysqli_close($conn);

?>
