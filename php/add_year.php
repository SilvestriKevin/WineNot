<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once "../include/config.php";

//inclusione file per funzioni ausiliarie
include_once "../include/lib.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
}

$annata = '';
$info_errore = '';

if (!empty($_COOKIE['info'])) {
    $info_errore .= "<li>" . $_COOKIE['info'] . "</li>";
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= "<li id='error_admin_message'>" . $_COOKIE['error'] . "</li>";
    setcookie('error', null);
}

// qualsiasi tipo di utente può aggiungere una nuova annata

$annata .= '<h1 id="admin_title">Inserisci una nuova annata</h1>';

$annata .= '<form onsubmit="return fullyCheckYear()" id="panel_admin_form_add_year" action="add_year.php" method="post">';
$annata .= '<fieldset>
                    <ul>
                    <li id="important_message_year"><span>Tutti i campi sono obbligatori</span></li>
                    <li>
                        <label>Anno</label>
                    </li>
                    <li>
                        <span id="year_error" class="js_error"></span>
                    </li>
                    <li>
                        <input id="check_year" type="text" maxlength="4" name="anno" title="anno" tabindex="1" 
                        onfocusout="checkYear()"/>
                    </li>

                    <li>
                        <label>Descrizione</label>
                    </li>
                    <li>
                        <span id="description_error" class="js_error"></span>
                    </li>
                    <li>
                        <input id="check_description" type="textarea" maxlength="50" name="descrizione"
                         title="descrizione" tabindex="1" onfocusout="checkYearDescription()"/>
                    </li>

                    <li>
                        <label>Qualit&agrave;</label>
                    </li>
                    <li>
                        <span id="quality_error" class="js_error"></span>
                    </li>
                    <li>
                        <input id="check_quality" type="text" maxlength="100" name="qualita" title="qualita" tabindex="6" 
                        onfocusout="checkYearQuality()"/>
                    </li>

                    <li>
                        <label>Migliore </label>
                        <input type="checkbox" name="migliore" title="migliore" value="1" tabindex="7"/>
                    </li>

                    <li>
                        <input type="submit" class="search_button" name="salva" id="save_add_year" value="Aggiungi"
                        accesskey="s" tabindex="8"/>
                    </li>
                    </ul>

                </fieldset>';

//questo controllo va a buon fineà
if (isset($_POST['anno']) && isset($_POST['descrizione']) && isset($_POST['qualita'])) {

    // controllo che non siano stati lasciati campi vuoti.
    if (!empty($_POST['anno']) && !preg_match("/^(\s)+$/", $_POST['anno']) && !empty($_POST['descrizione']) && !preg_match("/^(\s)+$/", $_POST['descrizione']) && !empty($_POST['qualita']) && !preg_match("/^(\s)+$/", $_POST['qualita'])) {

        //dichiaro le variabili
        $anno = $_POST['anno'];
        $descrizione = $_POST['descrizione'];
        $qualita = $_POST['qualita'];

        // controllo che l'anno sia del formato giusto
        if (is_numeric($anno) && strlen($anno) == 4 && !preg_match("/^(\s)+$/", $anno)) {

            // controllo che l'anno non sia già presente all'interno del database
            $sql = "SELECT anno FROM annate WHERE anno='" . $anno . "'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
                setcookie('error', "L'anno inserito &egrave; gi&agrave; presente nel database.");
                header("Location: add_year.php");
            } else {
                // inserisco i dati nel database
                if (!isset($_POST['migliore'])) {
                    $sql = "INSERT INTO annate (anno, descrizione,qualita) VALUES ('" . $anno . "','" . $descrizione . "', '" . $qualita . "')";
                } else {
                    $sql = "INSERT INTO annate (anno, descrizione,qualita,migliore) VALUES ('" . $anno . "','" . $descrizione . "', '" . $qualita . "', '1')";
                }
                //controllo la connessione
                if (mysqli_query($conn, $sql)) {
                    setcookie('info', "Aggiunta avvenuta con successo.");
                    header("Location: admin_years.php");
                } else {
                    setcookie('error', "Si &egrave; verificato un errore. La preghiamo di riprovare");
                    header("Location: add_year.php");
                }
            }
        } else {
            setcookie('error', 'Anno non è nel formato corretto (es. 1994).');
            header("Location: add_year.php");
        }

    } else {
        setcookie('error', 'Alcuni campi risultano vuoti.');
        header("Location: add_year.php");
    }
}
$annata .= '</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina

$pagina = str_replace("[SEARCH_WINE]", '', $pagina);

$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $annata, $pagina);
mysqli_close($conn);
