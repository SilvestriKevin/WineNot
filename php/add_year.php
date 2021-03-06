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

//FORM INSERIMENTO ANNATA - qualsiasi tipo di utente può aggiungere una nuova annata
$annata .= '<h1 id="admin_title">Inserimento annata</h1>';
$annata .= '<form onsubmit="return fullyCheckYear()" id="panel_admin_form_add_year" action="add_year.php" method="post">';
$annata .= '<fieldset>
                    <ul>
                    <li id="important_message_year"><span>Tutti i campi sono obbligatori</span></li>
                    <li class="label_add">
                        <label>Anno</label>
                    </li>
                    <li>
                        <span id="year_error" class="js_error"></span>
                    </li>
                    <li>
                        <input class="input_add" id="check_year" type="text" maxlength="4" name="anno" title="anno" tabindex="6"
                        onblur="checkYear()"/>
                    </li>

                    <li class="label_add">
                        <label>Descrizione</label>
                    </li>
                    <li>
                        <span id="description_error" class="js_error"></span>
                    </li>
                    <li>
                        <textarea id="check_description" name="descrizione" title="descrizione" onblur="checkYearDescription()" tabindex="7" rows="7" cols="34"></textarea>
                    </li>

                    <li class="label_add">
                        <label>Qualit&agrave;</label>
                    </li>
                    <li>
                        <span id="quality_error" class="js_error"></span>
                    </li>
                    <li>
                        <input class="input_add" id="check_quality" type="text" maxlength="100" name="qualita" title="qualita" tabindex="8"
                        onblur="checkYearQuality()"/>
                    </li>

                    <li class="label_add">
                        <label>Migliore</label>
                        <input type="checkbox" name="migliore" title="migliore" value="migliore" tabindex="9"/>
                    </li>
                    <input type="submit" class="search_button" name="salva" id="save_add_year" value="Aggiungi"
                        accesskey="s" tabindex="10"/>
                    </ul>
                </fieldset>';

//controllo che i campi del form siano stati settati
if (isset($_POST['anno']) && isset($_POST['descrizione']) && isset($_POST['qualita'])) {

    //controllo che non siano stati lasciati campi vuoti
    if (!empty($_POST['anno']) && !preg_match('/^(\s)+$/', $_POST['anno']) && !empty($_POST['descrizione']) &&
        !preg_match('/^(\s)+$/', $_POST['descrizione']) && !empty($_POST['qualita']) && !preg_match('/^(\s)+$/', $_POST['qualita'])) {
            
        //dichiarazione variabili
        $anno = $_POST['anno'];
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

            //controllo che l'anno non sia già presente nel database
            $sql = 'SELECT anno FROM annate WHERE anno="' . $anno . '"';
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
                setcookie('error', 'L&apos;anno inserito &egrave; gi&agrave; presente nel database.');
                header('Location: add_year.php');
            } else {
                //inserisco i dati nel database
                $sql = 'INSERT INTO annate (anno, descrizione,qualita,migliore) VALUES ("' . $anno . '","' . $descrizione 
                . '", "' . $qualita . '", migliore=' . $migliore . ')';
                
                //controllo la connessione
                if (mysqli_query($conn, $sql)) {
                    setcookie('info', 'Annata inserita con successo.');

                    //se il cookie è settato, assegno alla variabile $addWine l'indirizzo della pagina di inserimento vino
                    if (isset($_COOKIE['addWine'])) {
                        $addWine = $_COOKIE['addWine'];
                        unset($_COOKIE['addWine']);
                        setcookie('addWine', '', time() - 3600);
                        header('Location:' . $addWine);
                    }
                    //se il cookie è settato, assegno alla variabile $modifyWine l'indirizzo della pagina di modifica vino
                    else if (isset($_COOKIE['modifyWine'])) {
                        $modifyWine = $_COOKIE['modifyWine'];
                        unset($_COOKIE['modifyWine']);
                        setcookie('modifyWine', '', time() - 3600);
                        header('Location:' . $modifyWine);
                    }
                    //altrimenti riporto alla pagina di gestione annate
                    else {
                        header('Location: admin_years.php');
                    }
                } else {
                    setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare' . $sql);
                    header('Location: add_year.php');
                }
            }
        }
        //controllo il caso in cui l'anno sia maggiore dell'anno corrente
        else if (preg_match('/^\d{4}$/', $anno) && $anno > date('Y')) {
            setcookie('error', 'Anno deve essere minore o uguale dell&apos;anno corrente (' . date('Y') . ').');
            header('Location: add_year.php');
        }
        //controllo il caso in cui l'anno sia minore o uguale di 1900
        else if (preg_match('/^\d{4}$/', $anno) && $anno <= 1900) {
            setcookie('error', 'Anno deve essere maggiore dell&apos;anno 1900.');
            header('Location: add_year.php');
        } else {
            setcookie('error', 'Anno non è nel formato corretto (es. 1994).');
            header('Location: add_year.php');
        }

    } else {
        setcookie('error', 'Alcuni campi risultano vuoti.');
        header('Location: add_year.php');
    }
}

$annata .= '</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $annata, $pagina);

//chiudo la connessione
mysqli_close($conn);
