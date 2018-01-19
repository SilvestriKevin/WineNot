<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once "../include/config.php";

//inclusione file per funzioni ausiliarie
include_once "../include/lib.php";

//controllo se è settata la session, altrimenti si viene riportati alla pagina iniziale
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
}

//dichiarazione variabili
$vino = '';
$info_errore = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= "<li>" . $_COOKIE['info'] . "</li>";
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= "<li id='error_admin_message'>" . $_COOKIE['error'] . "</li>";
    setcookie('error', null);
}

//FORM INSERIMENTO VINO - qualsiasi tipo di utente può aggiungere un nuovo vino
$vino .= '<h1 id="admin_title">Inserisci un nuovo vino</h1>';
$vino .= '<form onsubmit="return checkWine()" id="panel_admin_form_add_wine" enctype="multipart/form-data" action="add_wine.php" method="post">';
$vino .= '<fieldset><ul>';
$vino .= '<li><label>Annata</label></li><li>';

//vengono mostrate le annate presenti nel database
$sql = "SELECT anno FROM annate ORDER BY anno";
$result = mysqli_query($conn, $sql);
//se ci sono annate allora le stampo in una select
if (mysqli_num_rows($result) != 0) {
    $vino .= '<select name="annata">';
    while ($subrow = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $vino .= "<option value='" . $subrow['anno'] . "'>" . $subrow['anno'] . "</option>";
    }
    $vino .= '</select>';
} else {
    $vino .= '<label>Non sono presenti annate. </label>';
}

//setto un cookie che mi servirà per ritornare a questo form dopo aver inserito una nuova annata
setcookie('addWine', $_SERVER['PHP_SELF']);

//nel caso l'utente voglia aggiungere ua nuova annata
$vino .= "<a title='Aggiungi annata' class='' href='./add_year.php' tabindex='' accesskey=''>Aggiungi Annata</a></li>
<li>
<label>Nome</label>
</li>
<li>
<span id='wine_name_error' class='js_error'></span>
</li>
<li>
<input type='text' maxlength='30' name='nome' title='nome' onfocusout='checkNome()' />
</li>

<li>
<label>Tipologia</label>
</li>
<li>
<span id='wine_tipologia_error' class='js_error'></span>
</li>
<li>
<input type='text' maxlength='30' name='tipologia' title='tipologia' onfocusout='checkTipologia()' />
</li>

<li>
<label>Vitigno</label>
</li>
<li>
<span id='wine_vitigno_error' class='js_error'></span>
</li>
<li>
<input type='textarea' maxlength='30' name='vitigno' title='vitigno' onfocusout='checkVitigno()' />
</li>

<li>
<label>Denominazione</label>
</li>
<li>
<span id='wine_denominazione_error' class='js_error'></span>
</li>
<li>
<input type='text' maxlength='30' name='denominazione' title='denominazione' onfocusout='checkDenominazione()'/>
</li>

<li>
<label>Gradazione(%)</label>
</li>
<li>
<span id='wine_gradazione_error' class='js_error'></span>
</li>
<li>
<input type='text' maxlength='4' name='gradazione' title='gradazione' onfocusout='checkGradazione()' />
</li>

<li>
<label>Formato(l)</label>
</li>
<li>
<span id='wine_formato_error' class='js_error'></span>
</li>
<li>
<input type='text' maxlength='4' name='formato' title='formato' onfocusout='checkFormato()'/>
</li>

<li>
<label>Descrizione</label>
</li>
<li>
<span id='wine_descrizione_error' class='js_error'></span>
</li>
<li>
<input type='textarea' name='descrizione' title='descrizione' onfocusout='checkDescrizione()'/>
</li>

<li>
<label>Abbinamento</label>
</li>
<li>
<span id='wine_abbinamento_error' class='js_error'></span>
</li>
<li>
<input type='textarea' name='abbinamento' title='abbinamento' onfocusout='checkAbbinamento()'/>
</li>

<li>
<label>Degustazione</label>
</li>
<li>
<span id='wine_degustazione_error' class='js_error'></span>
</li>
<li>
<input type='textarea' name='degustazione' title='degustazione' onfocusout='checkDegustazione()'/>
</li>

<li>
<span id='wine_picture_error' class='js_error'></span>
</li>
<li>
<input id='select_file' type='file' name='wine_img' />
</li>

<li>
<input type='submit' class='search_button' name='save_profile' id='save_add_wine' value='Salva' />
</li>
</ul></fieldset>";

//controllo se l'utente ha provato a salvare i dati del form
if (!empty($_POST['save_profile'])) {
    //controllo che tutti i campi siano non vuoti e non contengano solo spazi ("   ")
    if (!empty($_POST['nome']) && !preg_match("/^(\s)+$/", $_POST['nome']) && !empty($_POST['tipologia']) && 
    !preg_match("/^(\s)+$/", $_POST['tipologia']) && !empty($_POST['descrizione']) && !preg_match("/^(\s)+$/", $_POST['descrizione']) 
    && !empty($_POST['denominazione']) && !preg_match("/^(\s)+$/", $_POST['denominazione']) && !empty($_POST['annata']) 
    && !empty($_POST['vitigno']) && !preg_match("/^(\s)+$/", $_POST['vitigno']) && !empty($_POST['abbinamento']) && 
    !preg_match("/^(\s)+$/", $_POST['abbinamento']) && !empty($_POST['degustazione']) && !preg_match("/^(\s)+$/", $_POST['degustazione']) 
    && !empty($_POST['gradazione']) && !preg_match("/^(\s)+$/", $_POST['gradazione']) && !empty($_POST['formato']) && 
    !preg_match("/^(\s)+$/", $_POST['formato']) && !empty($_FILES['wine_img']) && $_FILES['wine_img']['type'] == "image/png") {

        //dichiarazione variabili
        $nome = $_POST['nome'];
        $tipologia = $_POST['tipologia'];
        $descrizione = $_POST['descrizione'];
        $denominazione = $_POST['denominazione'];
        $annata = $_POST['annata'];
        $vitigno = $_POST['vitigno'];
        $abbinamento = $_POST['abbinamento'];
        $degustazione = $_POST['degustazione'];
        $gradazione = $_POST['gradazione'];
        $formato = $_POST['formato'];
        $file = $_FILES['wine_img'];
        $error = '';

        // DA FINIRE
        // ESEMPI DI CONTROLLI A SEGUIRE

        //controllo dell'anno: non è presente perchè l'utente può solo selezionare un'annata dalla select

        //controllo gradazione: 
        if (strlen($gradazione) <= 3 || !preg_match("/\d\.\d/", strval($gradazione)) ||
            preg_match("/^(\s)+$/", strval($gradazione))) {
            $error .= 'Gradazione non è nel formato giusto./n';
        }

        // controllo formato

        if (strlen($formato) != 4 || !preg_match("/\d\.\d{2}/", $formato) || preg_match("/^(\s)+$/", $formato)) {
            $error .= 'Formato non è nel formato giusto./n';
        }

        // controllo che con i files sia tutto ok

        if ($file['error'] != UPLOAD_ERR_OK && !is_uploaded_file($file['tmp_name'])) {
            $error .= "C'&egrave; stato un problema con il caricamento dell'immagine. La preghiamo di riprovare./n";
        }

        if (!empty($error)) {
            setcookie('error', $error);
            header("Location: add_wine.php");
        } else {

            $sql = "SELECT * FROM vini WHERE annata='" . $annata . "' AND nome='" . $nome . "' AND tipologia='" . $tipologia . "' AND denominazione='" . $denominazione . "'";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
                setcookie('error', "Un vino con queste informazioni &egrave; stato gi&agrave; inserito.");
            } else {
                // posso inserire il vino all'interno del database

                $sql = "INSERT INTO vini (nome, tipologia, descrizione, denominazione, annata, vitigno, abbinamento, degustazione, gradazione, formato) VALUES ('" . $nome . "','" . $tipologia . "', '" . $descrizione . "','" . $denominazione . "','" . $annata . "','" . $vitigno . "','" . $abbinamento . "','" . $degustazione . "','" . $gradazione . "','" . $formato . "')";

                //controllo la connessione
                if (mysqli_query($conn, $sql) == true) {

                    // ho aggiunto il vino al database
                    // ora posso far sì che venga aggiunta anche la foto
                    // che ricordiamo non serve salvare alcun dato nel db dato
                    // che ci servirà solamente l'id_wine

                    // LAST_INSERT_ID() ritorna l'ultimo id creato
                    // attraverso autoincrement
                    $sql = "SELECT LAST_INSERT_ID() as id_wine";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) != 0) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    } else {
                        setcookie("C'&egrave; stato un errore con l'inserimento dell'immagine, la preghiamo di riprovare provando a fare la Modifica del vino.");
                        header("Location: add_wine.php");
                    }
                    // dò il nome che mi serve alla foto (cioè id_wine)
                    $file['name'] = $row['id_wine'];
                    // e lo sposto nella cartella giusta

                    // move_uploaded_file ritorna TRUE se tutto va bene
                    if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/WineNot/img/" . $file['name'] . ".png")) {
                        setcookie('info', "Aggiunta avvenuta con successo.");

                        //se il cookie è settato, lo unsetto
                        if (isset($_COOKIE['addWine'])) {
                            unset($_COOKIE['addWine']);
                            setcookie('addWine', '', time() - 3600);
                        }
                        header("Location: admin_wines.php");
                    } else { // il caricamento del file non è andato a buon fine
                        setcookie('error', "Il caricamento dell'immagine non &egrave; andato a buon fine. La preghiamo di riprovare ad inserire l'immagine attraverso la Modifica del vino.");
                        header("Location: modify_wine.php?idwine=" . $file['name'] . "");
                    }

                } else {
                    setcookie('error', "Si &egrave; verificato un errore. La preghiamo di riprovare");
                    header("Location: add_wine.php");
                }
            }
        }
    } 
    else {
        setcookie('error', 'Alcuni campi risultano vuoti.');
        header("Location: add_wine.php");
    }

}

$vino .= '</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $vino, $pagina);

//chiudo la connessione
mysqli_close($conn);
