<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//inclusione file per funzioni ausiliarie
include_once '../include/lib.php';

//dichiarazione variabili
$vini = '';
$annata = '';
$tipologia = '';
$ordine = '';
$improved_search = '';
$text_searched = '';
$lista = '';

//assegno la query string nell'url ad un cookie che mi servirà per tornare alla ricerca da dentro la pagina di un vino specifico
$url = $_SERVER['PHP_SELF'];
if (!empty($_SERVER['QUERY_STRING'])) {
    $url .= '?' . $_SERVER['QUERY_STRING'];
}

setcookie('indietro', $url);

//SELECT ANNATA NEL FORM
$sql = 'SELECT annata FROM vini GROUP BY annata ORDER BY annata';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $annata .= '<option value="' . $row['annata'] . '"';
        if (!empty($_GET['annata']) && $_GET['annata'] == $row['annata']) {
            $annata .= ' selected="selected"';
        }

        $annata .= '>' . $row['annata'] . '</option>';
    }
}

//SELECT TIPOLOGIA NEL FORM
$array_tipologie = array('bianco', 'rosso', 'ros&eacute;');
$num_elementi = count($array_tipologie);
for ($i = 0; $i < $num_elementi; $i++) {
    $tipologia .= '<option value="' . $array_tipologie[$i] . '"';
    if (!empty($_GET['tipologia']) && entityAccentedVowels($_GET['tipologia']) == $array_tipologie[$i]) {
        $tipologia .= ' selected="selected"';
    }

    $tipologia .= '>' . $array_tipologie[$i] . '</option>';
}

//SELECT ORDINE NEL FORM
$array_ordine = array('nome', 'annata', 'tipologia', 'gradazione');
$num_elementi = count($array_ordine);
for ($i = 0; $i < $num_elementi; $i++) {
    $ordine .= '<option value="' . $array_ordine[$i] . '"';
    if (!empty($_GET['ordine']) && $_GET['ordine'] == $array_ordine[$i]) {
        $ordine .= ' selected="selected"';
    }

    $ordine .= '>' . $array_ordine[$i] . '</option>';
}

$text_search = 'vini';
$counter_tabindex = 10;

//STAMPA I VINI SECONDO I PARAMETRI DI RICERCA
if (!empty($_GET['annata']) && !empty($_GET['tipologia']) && !empty($_GET['ordine'])) {

    if (!empty($_GET['search'])) {
        //assegno il testo cercato nella barra di testo ad una variabile per poi stamparlo successivamente a schermo
        $text_searched = '<div>Hai cercato: "' . htmlentities($_GET['search'], ENT_QUOTES) . '"</div>';

        //chiamo la funzione in lib.php che controlla il testo inserito e pulisce la stringa
        $search = cleanInput($_GET['search']);

        $counter = 0;
        while (!empty($search[$counter])) {

            if ($counter > 0) {
                $text_search = '( SELECT vini.* FROM ' . $text_search . ' WHERE ( vini.nome LIKE "%' . $search[$counter] . '%" OR
                vini.tipologia LIKE "%' . $search[$counter] . '%" OR vini.gradazione LIKE "%' . $search[$counter] . '%" OR vini.annata LIKE
                "%' . $search[$counter] . '%" ) ) AS vini';
            } else {
                $text_search = '( SELECT vini.* FROM vini WHERE ( vini.nome LIKE "%' . $search[$counter] . '%" OR vini.tipologia
                LIKE "%' . $search[$counter] . '%" OR vini.gradazione LIKE "%' . $search[$counter] . '%" OR vini.annata LIKE
                "%' . $search[$counter] . '%" ) ) AS vini';
            }

            $counter++;

        }
    }

    if ($_GET['annata'] != 'All') {
        $improved_search .= ' WHERE annata="' . htmlentities($_GET['annata'], ENT_QUOTES) . '"';
    }

    if ($_GET['tipologia'] != 'All') {
        if (!empty($improved_search)) {
            $improved_search .= ' AND tipologia="' . htmlentities($_GET['tipologia'], ENT_QUOTES) . '"';
        } else {
            $improved_search .= ' WHERE tipologia="' . htmlentities($_GET['tipologia'], ENT_QUOTES) . '"';
        }
    }

    //STAMPA I VINI
    $sql = 'SELECT vini.* FROM ' . $text_search . $improved_search . ' ORDER BY ' . htmlentities($_GET['ordine'], ENT_QUOTES);

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) != 0) {
        $vini.='<div id="title_our_wines">
        <h1>I nostri vini</h1>
        </div><ul>';
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $vini .= '<li><div class="specific_result specific_wine">
            <a title="' . $row['nome'] . '" href="../php/wine.php?id_wine=' . $row['id_wine'] . '" tabindex="' . $counter_tabindex++ . '">
            <img alt="' . $row['nome'] . '" src="../img/';

            //controllo che sia presente l'immagine del vino nel server, altrimenti mostro l'immagine di default
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/WineNot/img/' . $row["id_wine"] . '.png')) {
                $vini .= $row["id_wine"];
            } else {
                $vini .= 'default_wine';
            }

            $vini .= '.png"/></a>';
            $vini .= '<ul><li><label>Nome: </label>' . $row['nome'] . '</li>';
            $vini .= '<li><label>Tipologia: </label>' . $row['tipologia'] . '</li>';
            $vini .= '<li><label>Annata: </label>' . $row['annata'] . '</li>';
            $vini .= '<li><label>Gradazione: </label>' . $row['gradazione'] . '</li>';
            $vini .= '</ul></div></li>';
        }
        $vini .= '</ul>';

        if (mysqli_num_rows($result) > 4) {
        $vini.='<a title="Torna Su" href="#header" id="go_up" tabindex="998" accesskey="g">Torna su</a>';
        }
        
    } else {
        $vini .= '<h1 id="no_results_page">Non sono presenti vini per questa ricerca. Riprova cambiando i parametri.</h1>';
    }

} else {
    //STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
    $sql = 'SELECT vini.* FROM vini ORDER BY nome';
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) != 0) {
        $vini.='<div id="title_our_wines">
        <h1>I nostri vini</h1>
        </div><ul>';
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $vini .= '<li>
            <div class="specific_result specific_wine">
            <a title="' . $row['nome'] . '" href="../php/wine.php?id_wine=' . $row['id_wine'] . '" tabindex="' . $counter_tabindex++ . '">
            <img alt="' . $row['nome'] . '" src="../img/';

            //controllo che sia presente l'immagine del vino nel server, altrimenti mostro l'immagine di default
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/WineNot/img/' . $row["id_wine"] . '.png')) {
                $vini .= $row["id_wine"];
            } else {
                $vini .= 'default_wine';
            }

            $vini .= '.png"/></a>';
            $vini .= '<ul><li><label>Nome: </label>' . $row['nome'] . '</li>';
            $vini .= '<li><label>Tipologia: </label>' . $row['tipologia'] . '</li>';
            $vini .= '<li><label>Annata: </label>' . $row['annata'] . '</li>';
            $vini .= '<li><label>Gradazione: </label>' . $row['gradazione'] . '</li>';
            $vini .= '</ul></div></li>';
        }
        $vini .= '</ul>';

        if (mysqli_num_rows($result) > 4) {
        $vini.='<a title="Torna Su" href="#header" id="go_up" tabindex="998" accesskey="g">Torna su</a>';
        }
        
    } else {
        $vini .= '<h1 id="no_results_page">Non sono presenti vini.</h1>';
    }

}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/wines.html');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[ANNATA]', $annata, $pagina);
$pagina = str_replace('[TIPOLOGIA]', $tipologia, $pagina);
$pagina = str_replace('[ORDINE]', $ordine, $pagina);
$pagina = str_replace('[TESTO_CERCATO]', $text_searched, $pagina);
echo str_replace('[VINI]', $vini, $pagina);

//chiudo la connessione
mysqli_close($conn);
