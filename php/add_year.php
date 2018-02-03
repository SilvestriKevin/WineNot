<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//inclusione file per funzioni ausiliarie
include_once '../include/lib.php';

//controllo se è settata la session, altrimenti si viene riportati alla pagina iniziale
if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
}

//dichiarazione variabili
$annata = '';
$info_errore = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<li>' . $_COOKIE['info'] . '</li>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<li id="error_admin_message">' . $_COOKIE['error'] . '</li>';
    setcookie('error', null);
}

//FORM INSERIMENTO ANNATA - qualsiasi tipo di utente può aggiungere una nuova annata
$annata .= '<h1 id="admin_title">Inserisci una nuova annata</h1>';
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
                    <li class="input_add">
                        <input id="check_year" type="text" maxlength="4" name="anno" title="anno" tabindex="1"
                        onfocusout="checkYear()"/>
                    </li>

                    <li class="label_add">
                        <label>Descrizione</label>
                    </li>
                    <li>
                        <span id="description_error" class="js_error"></span>
                    </li>
                    <li>
                        <textarea id="check_description" maxlength="50" name="descrizione"
                         title="descrizione" tabindex="1" onblur="checkYearDescription()" rows="4" cols="34">
                        </textarea>
                    </li>

                    <li class="label_add">
                        <label>Qualit&agrave;</label>
                    </li>
                    <li>
                        <span id="quality_error" class="js_error"></span>
                    </li>
                    <li class="input_add">
                        <input id="check_quality" type="text" maxlength="100" name="qualita" title="qualita" tabindex="6"
                        onfocusout="checkYearQuality()"/>
                    </li>

                    <li class="label_add">
                        <label>Migliore</label>
                        <input type="checkbox" name="migliore" title="migliore" value="migliore" tabindex="7"/>
                    </li>
                    </ul>
                    
                    <input type="submit" class="search_button" name="salva" id="save_add_year" value="Aggiungi"
                        accesskey="s" tabindex="8"/>

                </fieldset>';

//controllo che i campi del form siano stati settati
if (isset($_POST['anno']) && isset($_POST['descrizione']) && isset($_POST['qualita'])) {

    //controllo che non siano stati lasciati campi vuoti
    if (!empty($_POST['anno']) && !preg_match('/^(\s)+$/', $_POST['anno']) && !empty($_POST['descrizione']) && 
    !preg_match('/^(\s)+$/', $_POST['descrizione']) && !empty($_POST['qualita']) && !preg_match('/^(\s)+$/', $_POST['qualita'])) {

        //dichiarazione variabili
        $anno = $_POST['anno'];
        $descrizione = $_POST['descrizione'];
        $qualita = $_POST['qualita'];

        //controllo che l'anno sia del formato corretto oltre ad essere maggiore di 1900 e minore uguale dell'anno corrente
        //es. 2004
        if (preg_match('/^\d{4}$/', $anno) && $anno > 1900 && $anno <= date('Y')) {

            //controllo che l'anno non sia già presente nel database
            $sql = 'SELECT anno FROM annate WHERE anno="' . $anno . '"';
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
                setcookie('error', 'L&apos;anno inserito &egrave; gi&agrave; presente nel database.');
                header('Location: add_year.php');
            } 
            else {
                //inserisco i dati nel database
                if (!isset($_POST['migliore'])) {
                    $sql = 'INSERT INTO annate (anno, descrizione,qualita) VALUES ("' . $anno . '","' . $descrizione . '", "' . $qualita . '")';
                } else {
                    $sql = 'INSERT INTO annate (anno, descrizione,qualita,migliore) VALUES ("' . $anno . '","' . $descrizione . '", "' . $qualita . '", "1")';
                }
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
                    //altrimenti riporto alla pagina di gestione annate
                    else {
                        header('Location: admin_years.php');
                    }
                } 
                else {
                    setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare'.$sql);
                    header('Location: add_year.php');
                }
            }
        } 
        //controllo il caso in cui l'anno sia maggiore dell'anno corrente
        else if(preg_match('/^\d{4}$/', $anno) && $anno > date('Y')){
            setcookie('error', 'Anno deve essere minore o uguale dell&apos;anno corrente ('.date('Y').').');
            header('Location: add_year.php');
        } 
        //controllo il caso in cui l'anno sia minore o uguale di 1900
        else if(preg_match('/^\d{4}$/', $anno) && $anno <= 1900){
            setcookie('error', 'Anno deve essere maggiore dell&apos;anno 1900.');
            header('Location: add_year.php');
        }
        else {
            setcookie('error', 'Anno non è nel formato corretto (es. 1994).');
            header('Location: add_year.php');
        }

    } 
    else {
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
