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
    header('Location: admin_years.php');
}

//in $_POST['years'] sono contenuti tutti gli id dei vini che si vogliono eliminare
//se è settata anche $_POST['confirm'] allora procedo all'eliminazione
else if (!empty($_POST['years']) && !empty($_POST['confirm'])) {
    $years = $_POST['years'];
    $num_elem = count($years);
    $year_failed = array();

    //scorro le annate selezionate e controllo che non ci siano vini di quelle annate. Se un annata ha dei vini allora la salvo nell'array $year_failed
    for ($i = 0; $i < $num_elem; $i++) {
        $sql = 'SELECT id_wine FROM vini INNER JOIN annate ON vini.annata = annate.anno WHERE anno = "' . $years[$i] . '"';
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) != 0) {
            $year_failed[] = $years[$i];
        }

    }

    //se nessuna annata selezionata ha dei vini allora procedo all'eliminazione
    if (empty($year_failed)) {

        //creazione della query inserendo tutti le annate selezionate
        $sql = 'DELETE FROM annate WHERE anno = "';
        for ($i = 0; $i < $num_elem; $i++) {
            if ($i != 0) {
                $sql .= '" OR anno = "';
            }

            $sql .= $years[$i];
        }
        $sql .= '"';

        $result = mysqli_query($conn, $sql);

        //controllo la connessione
        if ($result) {
            $message = 'Eliminazione avvenuta con successo. ';
            if ($num_elem == 1) {
                $message .= 'Eliminata 1 annata.';
            } else {
                $message .= 'Eliminate ' . $num_elem . ' annate.';
            }

            setcookie('info', $message);
        } else {
            setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare');
        }

    }
    //alcune annate hanno dei vini
    else {
        //scorro l'array $year_failed e stampo le annate che hanno dei vini
        $num_elem = count($year_failed);
        $error = 'Sono presenti vini per le annate [';
        for ($i = 0; $i < $num_elem; $i++) {
            if ($i != 0) {
                $error .= ', ';
            }

            $error .= $year_failed[$i];
        }
        $error .= ']. La preghiamo di eliminare prima tutti i vini relativi e poi le annate.';

        setcookie('error', $error);
    }

    //ritorno in ogni caso alla gestione dei vini, dove verrà stampato un messagio di conferma o di errore
    header('Location: admin_years.php');
}
//in $_GET['years'] sono contenuti tutti gli id dei vini che si vogliono eliminare
//se è settato solo $_GET['years'] allora mostro la richiesta di conferma per l'eliminazione
else if (!empty($_GET['years'])) {
    $dati .= '<form onsubmit="return finalDeletion()" id="select_admin_buttons" action="delete_year.php" method="post">';

    $dati .= '<div class="select_admin_buttons"><input type="submit" class="admin_button" name="cancel" id="cancel" value="Annulla Eliminazione"
    onclick="goBackWines()" tabindex="7"/>';
    $dati .= '<input type="submit" class="admin_button" name="confirm" id="confirm" value="Conferma Eliminazione"
    onclick="confirmDeletion()" tabindex="8"/></div>';

    //controllo che nell'url abbia un array serializzato o un singolo dato
    //quindi provo a fare unserialize e se fallisce allora deduco di avere un dato unico
    //N.B.:inserire @ prima di una chiamata di funzione, evita che vengano mostrati errori che potrebbero essere lanciati da quella funzione e che potrebbero bloccare l'esecuzione del codice. === significa 'identico' mentre == significa 'uguale'
    if (($result = @unserialize($_GET['years'])) === false) {
        $year = $_GET['years'];
        $sql = 'SELECT annate.* FROM annate WHERE anno = "' . $year . '"';
    }
    //altrimenti sono sicuro di avere un array serializzato
    else {
        $years = unserialize($_GET['years']); //estrapolo i dati dall'array
        $num_elem = count($years);
        $sql = 'SELECT annate.* FROM annate WHERE anno = "';
        for ($i = 0; $i < $num_elem; $i++) {
            if ($i != 0) {
                $sql .= '" OR anno = "';
            }

            $sql .= $years[$i];
        }
        $sql .= '"';
    }

    $result = mysqli_query($conn, $sql);

    $dati .= '<div class="admin_tr" id="admin_header">
                            <div id="menu_select" class="admin_td">Selezione</div>
                            <div class="admin_td">Annata</div>
                            <div class="admin_td">Qualit&agrave;</div>
                            <div class="admin_td">Migliore</div>
                    </div>';

    $counter_index = 9;

    if (mysqli_num_rows($result) != 0) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dati .= '<div class="admin_tr">';
            $dati .= '<div class ="admin_td delete_year_checkbox_column"><input class="delete_year_checkbox" type="checkbox"
            name="years[]" value="' . $row['anno'] . '" checked="checked" onclick="removeErrorMessage()" tabindex="'.$counter_index++.'"/></div>';
            $dati .= '<div class ="admin_td delete_year_year_column">' . $row['anno'] . '</div>';
            $dati .= '<div class ="admin_td delete_year_quality_column">' . $row['qualita'] . '</div>';
            $dati .= '<div class ="admin_td delete_year_best_column">';
            if ($row['migliore'] == 0) {
                $dati .= 'No';
            } else {
                $dati .= 'Si';
            }

            $dati .= '</div>';
            $dati .= '</div>';
        }
    } else {
        header('Location: admin_years.php');
    }

    $dati .= '</form>';
}
//questo ramo if si verifica se si deselezionano tutte le annate precedentemente scelte per essere eliminate e poi si clicca su 'conferma eliminazione'
else if (!empty($_POST['confirm'])) {
    setcookie('error', 'Nessuna annata selezionata. Eliminazione annullata.');

    //ritorno alla gestione delle annate
    header('Location: admin_years.php');
}
//se la variabile non è settata significa che è stato manomesso l'url, allora riporto l'utente alla pagina amministrazione
else {
    header('Location: admin_years.php');
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $dati, $pagina);
mysqli_close($conn);
