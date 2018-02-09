<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//dichiarazione variabili
$vini = '';
$annate = '';
$description_annata = '';

//assegno la query string nell'url ad un cookie che mi servirà per tornare alla ricerca da dentro la pagina di un vino specifico
$url = $_SERVER['PHP_SELF'];
if (!empty($_SERVER['QUERY_STRING'])) {
    $url .= '?' . $_SERVER['QUERY_STRING'];
}

setcookie('indietro', $url);

$cont = 6;

//STAMPA LE ANNATE
$sql = 'SELECT annate.* FROM annate WHERE migliore=1';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 0) {
    $annate .= '<ul>';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $annate .= '<li><a title="' . $row['anno'] . '" ';
        if ((!empty($_GET['year']) && $_GET['year'] == $row['anno']) or (empty($_GET['year']) && $row['anno'] == '2016')) {
            $annate .= ' id="current_year" ';
        }

        $annate .= ' href="./best_years.php?year=' . $row['anno'] . '" tabindex="' . $cont . '">' . $row['anno'] . '</a></li>';
        $cont++;
    }
    $annate .= '</ul>';

    //STAMPA LE INFORMAZIONI DELL'ANNATA
    $sql = 'SELECT annate.* FROM annate WHERE anno=';
    if (!empty($_GET['year'])) {
        $sql .= '"' . $_GET['year'] . '" AND migliore=1';
    } else {
        $sql .= '(SELECT MAX(anno) FROM annate WHERE migliore=1)';
    }

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) != 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $description_annata .= '<ul><li><div id="year_quality"><ul><li><label>Anno </label><p>' . $row['anno'] . '</p></li>';

        $description_annata .= '<li><label>Qualit&agrave; </label><p>' . $row['qualita'] . '</p></li></ul></div></li>';

        $description_annata .= '<li><div id="year_description"><ul><li><label>Descrizione </label><p>' . $row['descrizione'] 
        . '</p></li></ul></div></li></ul>';
        //STAMPA I VINI DELL'ANNATA
        $sql = 'SELECT vini.* FROM vini WHERE annata="' . $row['anno'] . '"';
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) != 0) {
            $vini .= '<h2>I vini di questa annata:</h2><ul>';
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $vini .= '<li><div class="specific_wine specific_result">
            <a title="' . $row['nome'] . '" href="../php/wine.php?id_wine=' . $row['id_wine'] . '" tabindex="' . $cont . '">
            <img alt="' . $row['nome'] . '" src="../img/';

                //controllo che sia presente l'immagine del vino nel server, altrimenti mostro l'immagine di default
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/WineNot/img/' . $row["id_wine"] . '.png')) {
                    $vini .= $row["id_wine"];
                } else {
                    $vini .= 'default_wine';
                }

                $vini .= '.png"/></a><ul>';
                $vini .= '<li><label>Nome: </label>' . $row['nome'] . '</li>';
                $vini .= '<li><label>Tipologia: </label>' . $row['tipologia'] . '</li>';
                $vini .= '<li><label>Gradazione: </label>' . $row['gradazione'] . '</li>';
                $vini .= '</ul></div></li>';
            }
            $vini .= '</ul>';

            if (mysqli_num_rows($result) > 4) {
                $vini .= '<a title="Torna Su" href="#header" id="go_up" tabindex="998" accesskey="g">Torna su</a>';
            }

        } else {
            $vini .= '<h3>Non sono presenti vini per questa annata.</h3>';
        }

    }
    //se viene manomesso l'URL e l'anno inserito non è tra le annate migliori
    else if (!empty($_GET['year'])) {
        $description_annata .= '<h2>L&apos;annata selezionata non risulta tra le migliori.</h2>';
    }
} else {
    $description_annata .= '<h2>Non sono presenti annate migliori.</h2>';
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/best_years.html');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[ANNATE]', $annate, $pagina);
$pagina = str_replace('[DESCRIZIONE_ANNATA]', $description_annata, $pagina);
echo str_replace('[VINI_ANNATA]', $vini, $pagina);

//chiudo la connessione
mysqli_close($conn);
