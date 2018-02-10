<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//controllo se è settata la session, altrimenti si viene riportati alla pagina iniziale
if (!isset($_SESSION['id'])) {
    header('Location: ../index.html');
}

//dichiarazione variabili
$annata = '';
$info_errore = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<div class="info_sentence">' . $_COOKIE['info'] . '</div>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<div class="error_sentence">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//se è settato il cookie, mi salvo il suo valore in una variabile
if (!empty($_COOKIE['modifyYear'])) {
    $year = $_COOKIE['modifyYear'];
}
//altrimenti recupero l'anno dall'url e setto il cookie
else if (!empty($_GET['year'])) {
    //per evitare sql injection uso la funzione htmlentities() che converte ogni possibile carattere con l'entità HTML relativa
    $year = htmlentities($_GET['year'], ENT_QUOTES);
    setcookie('modifyYear', $year);
}
//infine se non è presente nessun riferimento all'anno, riporto alla pagina di gestione annate
else {
    header('Location: admin_years.php');
}

//controllo che sia stata inviata la submit
if (!empty($_POST['save_year'])) {
    //controllo che non siano stati lasciati campi vuoti
    if (!empty($_POST['anno']) && !preg_match('/^(\s)+$/', $_POST['anno']) && !empty($_POST['descrizione']) &&
        !preg_match('/^(\s)+$/', $_POST['descrizione']) && !empty($_POST['qualita']) && !preg_match('/^(\s)+$/', $_POST['qualita'])) {

        //dichiarazione variabili
        $anno = htmlentities($_POST['anno'], ENT_QUOTES);
        $descrizione = htmlentities($_POST['descrizione'], ENT_QUOTES);
        $qualita = htmlentities($_POST['qualita'], ENT_QUOTES);
        if ($_POST['migliore'] == false) {
            $migliore = 0;
        } else {
            $migliore = 1;
        }

        //controllo che l'anno sia del formato corretto oltre ad essere maggiore di 1900 e minore uguale dell'anno corrente
        //es. 2004
        if (preg_match('/^\d{4}$/', $anno) && $anno > 1900 && $anno <= date('Y')) {

            //controllo che sia stato modificato almeno un campo, altrimenti non serve fare l'update nel database
            $sql = 'SELECT * FROM annate WHERE anno=' . $anno . ' AND descrizione="' . $descrizione
                . '" AND qualita="' . $qualita . '" AND migliore=' . $migliore;

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                //controllo che questa annata modificata non esista già nel database (escludendo l'annata prima delle
                //modifiche ovviamente)
                $sql = 'SELECT * FROM (SELECT * FROM annate WHERE anno!=' . $year . ') AS anni WHERE anno=' . $anno;

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) != 0) {
                    setcookie('error', 'Questa annata &egrave; gi&agrave; presente nel database.');
                    header('Location: modify_year.php?year=' . $year);
                } else {

                    //aggiorno l'annata nel database
                    $sql = 'UPDATE annate SET anno="' . $anno . '", descrizione="' . $descrizione
                        . '", qualita="' . $qualita . '", migliore=' . $migliore . ' WHERE anno=' . $year;

                    //controllo la connessione
                    if (mysqli_query($conn, $sql)) {
                        //se il cookie è settato, lo unsetto
                        if (isset($_COOKIE['modifyYear'])) {
                            unset($_COOKIE['modifyYear']);
                            setcookie('modifyYear', '', time() - 3600);
                        }

                        setcookie('info', 'Modifica dei dati avvenuta con successo.');
                        //ritorno alla pagina di gestione annate
                        header('Location: admin_years.php ');
                    } else {
                        setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare.');
                        header('Location: modify_year.php?year=' . $year);
                    }

                }
            } //nel caso in cui l'utente abbia cercato di salvare, non avendo però modificato nessun dato, viene mostrato a video
            //il messaggio di 'modifica dei dati avvenuta con successo' per evitare il caso di metafora visiva
            else {
                setcookie('info', 'Modifica dei dati avvenuta con successo');
                header('Location: admin_years.php');
            }
        }
        //controllo il caso in cui l'anno sia maggiore dell'anno corrente
        else if (preg_match('/^\d{4}$/', $anno) && $anno > date('Y')) {
            setcookie('error', 'Anno deve essere minore o uguale dell&apos;anno corrente (' . date('Y') . ').');
            header('Location: modify_year.php?year=' . $year);
        }
        //controllo il caso in cui l'anno sia minore o uguale di 1900
        else if (preg_match('/^\d{4}$/', $anno) && $anno <= 1900) {
            setcookie('error', 'Anno deve essere maggiore dell&apos;anno 1900.');
            header('Location: modify_year.php?year=' . $year);
        } else {
            setcookie('error', 'Anno non è nel formato corretto (es. 1994).');
            header('Location: modify_year.php?year=' . $year);
        }

    } else {
        setcookie('error', 'Alcuni campi risultano vuoti.');
        header('Location: modify_year.php?year=' . $year);
    }
}

//FORM DATI ANNATA
$sql = 'SELECT * FROM annate WHERE anno="' . $year . '"';

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);

//se esiste un'annata con questo anno procedo
if (mysqli_num_rows($result) != 0) {
    $annata .= '<h1 id="admin_title">Modifica annata</h1>
    <form onsubmit="return fullyCheckYear()" id="panel_admin_form_add_wine" action="modify_year.php"
        method="post"><ul>
        <li><input type="hidden" name="year" value="' . $year . '" /></li>
        <li class="label_add">
            <label>Anno</label>
        </li>
        <li>
            <span id="year_error" class="js_error"></span>
        </li>
        <li>
            <input class="input_add" id="check_year" type="text" maxlength="4" name="anno" title="anno" value="' .
        $row['anno'] . '" onblur="checkYear()" tabindex="7"';

    $sql2 = 'SELECT id_wine FROM vini INNER JOIN annate ON vini.annata = annate.anno WHERE anno = ' . $year;

    $result2 = mysqli_query($conn, $sql2);

    //controllo che non ci siano vini di quest'annata, altrimenti disabilito la casella di testo
    if (mysqli_num_rows($result2) != 0) {
        $annata .= ' disabled="disabled" /></li>
            <li id="exist_already_wines">Sono presenti vini per quest&apos;annata. La modifica anno &egrave; dunque disabilitata.';
    } else {
        $annata .= '/>';
    }

    $annata .= '</li><li class="label_add">
            <label>Descrizione</label>
        </li>
        <li>
            <span id="description_error" class="js_error"></span>
        </li>
        <li>
            <textarea id="check_description" name="descrizione" title="descrizione" onblur="checkYearDescription()" rows="4" cols="34" tabindex="8">'
        . $row['descrizione'] . '</textarea>
        </li>

        <li class="label_add">
            <label>Qualit&agrave;</label>
        </li>
        <li>
            <span id="quality_error" class="js_error"></span>
        </li>
        <li>
            <input class="input_add" id="check_quality" type="text" maxlength="30" name="qualita" title="qualita" value="' .
        $row['qualita'] . '" onblur="checkYearQuality()" tabindex="9"
            />
        </li>

        <li class="label_add">
            <label>Migliore </label>
            <input type="checkbox" name="migliore" title="migliore" tabindex="10" ';

    if ($row['migliore'] == 1) {
        $annata .= 'checked="checked" /></li>';
    } else {
        $annata .= '/></li>';
    }

    $annata .= '<li><input type="submit" class="search_button" name="save_year" id="save_modify_year" value="Salva" tabindex="11"/></li>';
    $annata .= '</ul></form>';
} else {
    $annata .= '<h2>Non sono state trovate informazioni riguardo l&apos;annata selezionata.</h2>';
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html ');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $annata, $pagina);

//chiudo la connessione
mysqli_close($conn);
