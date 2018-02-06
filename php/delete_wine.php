<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//inclusione file per funzioni ausiliarie
include_once '../include/lib.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
}

$dati = '';
$info_errore = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<div id="top_message">' . $_COOKIE['info'] . '</div>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<div id="top_message">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//se è settato $_POST['cancel'] significa che l'utente ha deciso di annullare l'eliminazione
if (!empty($_POST['cancel'])) {
    header('Location: admin_wines.php');
}

//in $_POST['wines'] sono contenuti tutti gli id dei vini che si vogliono eliminare
//se è settata anche $_GET['delete_elements'] allora procedo all'eliminazione
else if (!empty($_POST['wines']) && !empty($_POST['confirm'])) {
    $wines = $_POST['wines'];
    $num_elem = count($wines);
    $sql = 'DELETE FROM vini WHERE id_wine = "';
    for ($i = 0; $i < $num_elem; $i++) {
        if ($i != 0) {
            $sql .= '" OR id_wine = "';
        }

        $sql .= $wines[$i];
    }
    $sql .= '"';
    $result = mysqli_query($conn, $sql);
    //controllo la connessione
    if ($result) {
        $message = 'Eliminazione avvenuta con successo. ';
        if ($num_elem == 1) {
            $message .= 'Eliminato 1 vino.';
        } else {
            $message .= 'Eliminati ' . $num_elem . ' vini.';
        }

        setcookie('info', $message);
    } else {
        setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare');
    }

    //ritorno in ogni caso alla gestione dei vini
    header('Location: admin_wines.php');
}
//in $_GET['wines'] sono contenuti tutti gli id dei vini che si vogliono eliminare
//se è settato solo $_GET['wines'] allora mostro la richiesta di conferma per l'eliminazione
else if (!empty($_GET['wines'])) {
    $dati .= '<form onsubmit="return finalDeletion()" id="select_admin_buttons" action="delete_wine.php" method="post">';

    $dati .= '<div><input type="submit" id="cancel_deletion" class="admin_button" name="cancel" 
     value="Annulla Eliminazione" onclick="goBackWines()" tabindex="7" />';
    $dati .= '<input type="submit" id="confirm_deletion" class="admin_button" name="confirm" 
     value="Conferma Eliminazione" onclick="confirmDeletion()" tabindex="8" /></div>';

    //controllo che nell'url abbia un array serializzato o un singolo dato
    //quindi provo a fare unserialize e se fallisce allora deduco di avere un dato unico
    //N.B.:inserire @ prima di una chiamata di funzione, evita che vengano mostrati errori che potrebbero essere lanciati da quella funzione e che potrebbero bloccare l'esecuzione del codice. === significa 'identico' mentre == significa 'uguale'
    if (($result = @unserialize($_GET['wines'])) === false) {
        $wine = $_GET['wines'];
        $sql = 'SELECT vini.* FROM vini WHERE id_wine = "' . $wine . '"';
    }
    //altrimenti sono sicuro di avere un array serializzato
    else {
        $wines = unserialize($_GET['wines']); //estrapolo i dati dall'array
        $num_elem = count($wines);
        $sql = 'SELECT vini.* FROM vini WHERE id_wine = "';
        for ($i = 0; $i < $num_elem; $i++) {
            if ($i != 0) {
                $sql .= '" OR id_wine = "';
            }

            $sql .= $wines[$i];
        }
        $sql .= '"';
    }

    $result = mysqli_query($conn, $sql);

    $dati .= '<div class="admin_tr" id="admin_header">
                            <div id="menu_select" class="admin_td">Selezione</div>
                            <div class="admin_td">Nome</div>
                            <div class="admin_td">Denominazione</div>
                            <div class="admin_td">Tipologia</div>
                            <div class="admin_td">Annata</div>
                    </div>';
    $counter_index = 9;
    if (mysqli_num_rows($result) != 0) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dati .= '<div class="admin_tr">';
            $dati .= '<div class ="admin_td delete_wine_checkbox_column">
            <input class="delete_wine_checkbox" type="checkbox" name="wines[]" value="' . $row['id_wine'] . '" checked="checked" onclick="removeErrorMessage()" tabindex="'.$counter_index++.'"/></div>';
            $dati .= '<div class ="admin_td delete_wine_name_column">' . $row['nome'] . '</div>';
            $dati .= '<div class ="admin_td delete_wine_denomination_column">' . $row['denominazione'] . '</div>';
            $dati .= '<div class ="admin_td delete_wine_tipology_column">' . $row['tipologia'] . '</div>';
            $dati .= '<div class ="admin_td delete_wine_year_column">' . $row['annata'] . '</div>';
            $dati .= '</div>';
        }
    } else {
        header('Location: admin_wines.php');
    }

    $dati .= '</form>';
}
//questo ramo if si verifica se si deselezionano tutti i vini precedentemente scelti per essere eliminati e poi si clicca su 'conferma eliminazione'
else if (!empty($_POST['confirm'])) {
    setcookie('error', 'Nessun vino selezionato. Eliminazione annullata.');

    //ritorno alla gestione dei vini
    header('Location: admin_wines.php');
}
//se la variabile non è settata significa che è stato manomesso l'url, allora riporto l'utente alla pagina amministrazione
else {
    header('Location: admin_wines.php');
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $dati, $pagina);
mysqli_close($conn);
