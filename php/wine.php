<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once "../include/config.php";

//inclusione file per funzioni ausiliarie
include_once "../include/lib.php";

//dichiarazione variabili
$immagine = "";
$descrizione = "";
$informazioni = "";

//in base a che cookie è settato, assegno alla variabile $indietro l'indirizzo che diverrà l'href del pulsante 'torna indietro'
if (isset($_COOKIE["indietro"])) {
    $indietro = $_COOKIE["indietro"];
    unset($_COOKIE["indietro"]);
    setcookie("indietro", "", time() - 3600);
}
//se ricado in questo caso significa che l'utente ha scritto direttamente l'url della pagina del vino specifico
//es.http://localhost/WineNot/php/wine.php?id_wine=3 senza passare per la pagina dei vini o delle annate
//allora imposto l'href verso la pagina iniziale
else {
    $indietro = '../index.html';
}

if (!empty($_GET["id_wine"])) {

    //STAMPA I DATI DEL VINO
    $sql = "SELECT vini.* FROM vini WHERE id_wine='" . $_GET['id_wine'] . "'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) != 0) {
        $immagine='<div>
        <a title="Torna indietro" href="[INDIETRO]" tabindex="6" accesskey="i">Torna indietro</a>
        </div>
        <h1>Descrizione</h1>
        <div id="img_description_wine">';
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $immagine .= '<img id="wine_img" alt="Immagine vino ' . $row["nome"] . '" src="../img/';

        //controllo che sia presente l'immagine del vino nel server, altrimenti mostro l'immagine di default
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/WineNot/img/' . $row["id_wine"] . '.png')) {
            $immagine .= $row["id_wine"];
        } else {
            $immagine .= 'default_wine';
        }

        $immagine .= '.png" /></div>';

        $descrizione .= $row["descrizione"];

        $informazioni .= '<ul><li class="title_details"><label>Dettagli </label></li>';

        $informazioni .= '<li><label>Nome: </label>' . $row["nome"] . '</li>';
        $informazioni .= '<li><label>Denominazione: </label>' . $row["denominazione"] . '</li>';
        $informazioni .= '<li><label>Tipologia: </label>' . $row["tipologia"] . '</li>';
        $informazioni .= '<li><label>Vitigno: </label>' . $row["vitigno"] . '</li>';
        $informazioni .= '<li><label>Annata: </label>' . $row["annata"] . '</li>';

        $informazioni .= '<li class="title_details"><label>Piatti e Occasioni</label></li>';

        $informazioni .= '<li><label>Abbinamento: </label>' . $row["abbinamento"] . '</li>';
        $informazioni .= '<li><label>Degustazione: </label>' . $row["degustazione"] . '</li>';

        $informazioni .= '<li class="title_details"><label>Quantità</label></li>';

        $informazioni .= '<li><label>Formato: </label>' . $row["formato"] . '</li>';
        $informazioni .= '<li><label>Gradazione: </label>' . $row["gradazione"] . '</li></ul>';
    } else {
        $informazioni .= '<h1>Non sono presenti informazioni su questo vino.</h1>';
    }

} else {
    $informazioni .= '<h1>Non ho capito a che vino ti stai riferendo.</h1>';
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
