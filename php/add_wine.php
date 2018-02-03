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
$vino = '';
$info_errore = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<li>' . $_COOKIE['info'] . '</li>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<div id="error_admin_message">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//FORM INSERIMENTO VINO - qualsiasi tipo di utente può aggiungere un nuovo vino
$vino .= '<h1 id="admin_title">Inserisci un nuovo vino</h1>';
$vino .= '<form onsubmit="return checkWine()" id="panel_admin_form_add_wine" enctype="multipart/form-data" action="add_wine.php" method="post">';
$vino .= '<fieldset><ul>';
$vino .= '<li><label>Annata</label></li><li>';

//vengono mostrate le annate presenti nel database
$sql = 'SELECT anno FROM annate ORDER BY anno';
$result = mysqli_query($conn, $sql);
//se ci sono annate allora le stampo in una select
if (mysqli_num_rows($result) != 0) {
    $vino .= '<select name="annata">';
    while ($subrow = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $vino .= '<option value="' . $subrow['anno'] . '">' . $subrow['anno'] . '</option>';
    }
    $vino .= '</select>';
} else {
    $vino .= '<label>Non sono presenti annate. </label>';
}

//setto un cookie che mi servirà per tornare a questo form dopo aver inserito una nuova annata
setcookie('addWine', $_SERVER['PHP_SELF']);

//CAMPI DEL FORM
$vino .= '</li><li><a title="Aggiungi annata" class="" href="./add_year.php" tabindex="" accesskey="">Aggiungi Annata</a></li>
<li class="label_add">
<label>Nome</label>
</li>
<li>
<span id="wine_name_error" class="js_error"></span>
</li>
<li class="input_add">
<input type="text" maxlength="30" name="nome" title="nome" onfocusout="checkNome()" />
</li>

<li class="label_add">
<label>Tipologia</label>
</li>
<li>
<span id="wine_tipologia_error" class="js_error"></span>
</li>
<li class="input_add">
<input type="text" maxlength="30" name="tipologia" title="tipologia" onfocusout="checkTipologia()" />
</li>

<li class="label_add">
<label>Vitigno</label>
</li>
<li>
<span id="wine_vitigno_error" class="js_error"></span>
</li>
<li>
<textarea maxlength="30" name="vitigno" title="vitigno" onblur="checkVitigno()" rows="4" cols="34"> 
</textarea>
</li>

<li class="label_add">
<label>Denominazione</label>
</li>
<li>
<span id="wine_denominazione_error" class="js_error"></span>
</li>
<li class="input_add">
<input type="text" maxlength="30" name="denominazione" title="denominazione" onfocusout="checkDenominazione()"/>
</li>

<li class="label_add">
<label>Gradazione(%)</label>
</li>
<li>
<span id="wine_gradazione_error" class="js_error"></span>
</li>
<li class="input_add">
<input type="text" maxlength="4" name="gradazione" title="gradazione" onfocusout="checkGradazione()" />
</li>

<li class="label_add">
<label>Formato(L)</label>
</li>
<li>
<span id="wine_formato_error" class="js_error"></span>
</li>
<li class="input_add">
<input type="text" maxlength="4" name="formato" title="formato" onfocusout="checkFormato()"/>
</li>

<li class="label_add">
<label>Descrizione</label>
</li>
<li>
<span id="wine_descrizione_error" class="js_error"></span>
</li>
<li>
<textarea name="descrizione" title="descrizione" onblur="checkDescrizione()" rows="4" cols="34">
</textarea>
</li>

<li class="label_add">
<label>Abbinamento</label>
</li>
<li>
<span id="wine_abbinamento_error" class="js_error"></span>
</li>
<li>
<textarea name="abbinamento" title="abbinamento" onblur="checkAbbinamento()" rows="4" cols="34">
</textarea>
</li>

<li class="label_add">
<label>Degustazione</label>
</li>
<li>
<span id="wine_degustazione_error" class="js_error"></span>
</li>
<li>
<textarea name="degustazione" title="degustazione" onblur="checkDegustazione()" rows="4" cols="34">
</textarea>
</li>

<li class="label_add">
<label>Immagine</label>
</li>
<li>
<span id="wine_picture_error" class="js_error"></span>
</li>
<li>
<input id="select_file" type="file" name="wine_img" />
</li>
</ul>
<input type="submit" class="search_button" name="save_profile" id="save_add_wine" value="Salva" />
</fieldset>';

//controllo se l'utente ha provato a salvare i dati del form
if (!empty($_POST['save_profile'])) {
    //controllo che tutti i campi siano non vuoti e non contengano solo spazi ('   ')
    if (!empty($_POST['nome']) && !preg_match('/^(\s)+$/', $_POST['nome']) && !empty($_POST['tipologia']) && 
    !preg_match('/^(\s)+$/', $_POST['tipologia']) && !empty($_POST['descrizione']) && !preg_match('/^(\s)+$/', $_POST['descrizione']) 
    && !empty($_POST['denominazione']) && !preg_match('/^(\s)+$/', $_POST['denominazione']) && !empty($_POST['annata']) 
    && !empty($_POST['vitigno']) && !preg_match('/^(\s)+$/', $_POST['vitigno']) && !empty($_POST['abbinamento']) && 
    !preg_match('/^(\s)+$/', $_POST['abbinamento']) && !empty($_POST['degustazione']) && !preg_match('/^(\s)+$/', $_POST['degustazione']) 
    && !empty($_POST['gradazione']) && !preg_match('/^(\s)+$/', $_POST['gradazione']) && !empty($_POST['formato']) && 
    !preg_match('/^(\s)+$/', $_POST['formato']) && !empty($_FILES['wine_img']) && $_FILES['wine_img']['type'] == 'image/png') {

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

        //CONTROLLO DI ALCUNI CAMPI DEL FORM

        //gradazione: il formato consentito è di 1 o 2 interi seguiti dal punto seguito poi da 1 sola cifra decimale
        //es. 7.5  oppure  13.5
        if (!preg_match('/^\d{1,2}\.\d$/', strval($gradazione))) {
            $error .= 'Gradazione non è nel formato corretto (es. 7.5% o 13.5%).<br />';
        }

        //formato: il formato corretto è 1 intero seguito dal punto seguito poi da 2 cifre decimali
        //es. 1.75  opuure  2.00  
        if (!preg_match('/^\d\.\d{2}$/', $formato)) {
            $error .= 'Formato non è nel formato corretto (es. 1.75L).<br />';
        }

        //immagine: controllo che sia stata caricata correttamente l'immagine
        if ($file['error'] != UPLOAD_ERR_OK && !is_uploaded_file($file['tmp_name'])) {
            $error .= 'C&apos;&egrave; stato un problema con il caricamento dell&apos;immagine. La preghiamo di riprovare.<br />';
        }

        //se $error non è vuota allora ricarico la pagina mostrando gli errori rilevati
        if (!empty($error)) {
            setcookie('error', $error);
            header('Location: add_wine.php');
        } 
        else {

            //controllo che non sia presente nel database lo stesso vino che sto cercando di inserire 
            $sql = 'SELECT * FROM vini WHERE annata="' . $annata . '" AND nome="' . $nome . '" AND tipologia="' . $tipologia . 
            '" AND denominazione="' . $denominazione . '"';

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
                setcookie('error', 'Un vino con questi dati &egrave; gi&agrave; presente nel database.');
                header('Location: add_wine.php');
            } 
            else {
                //posso procedere all'inserimento del vino nel database
                $sql = 'INSERT INTO vini (nome, tipologia, descrizione, denominazione, annata, vitigno, abbinamento, degustazione, 
                gradazione, formato) VALUES ("' . $nome . '","' . $tipologia . '", "' . $descrizione . '","' . $denominazione . 
                '","' . $annata . '","' . $vitigno . '","' . $abbinamento . '","' . $degustazione . '","' . $gradazione . '","' . 
                $formato . '")';

                //controllo la connessione
                if (mysqli_query($conn, $sql)) {

                    // ho aggiunto il vino al database
                    // ora posso far sì che venga aggiunta anche la foto
                    // N.B.: non serve salvare alcun dato nel db dato che ci servirà solamente l'id_wine

                    // LAST_INSERT_ID() ritorna l'ultimo id creato attraverso autoincrement
                    $sql = 'SELECT LAST_INSERT_ID() as id_wine';

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) != 0) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    } 
                    else {
                        setcookie('C&apos;&egrave; stato un errore con l&apos;inserimento dell&apos;immagine, la preghiamo di riprovare 
                        provando a fare la Modifica del vino.');
                        header('Location: add_wine.php');
                    }
                    // dò il nome che mi serve alla foto (cioè l'id_wine)
                    $file['name'] = $row['id_wine'];

                    // e lo sposto nella cartella corretta
                    // move_uploaded_file ritorna TRUE se tutto va bene
                    if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/WineNot/img/' . $file['name'] . '.png')) {
                        setcookie('info', 'Vino aggiunto con successo.');

                        //se il cookie è settato, lo unsetto
                        if (isset($_COOKIE['addWine'])) {
                            unset($_COOKIE['addWine']);
                            setcookie('addWine', '', time() - 3600);
                        }

                        //ritorno alla pagina della gestione vini
                        header('Location: admin_wines.php');
                    } 
                    else { // il caricamento del file non è andato a buon fine
                        setcookie('error', 'Il caricamento dell&apos;immagine non &egrave; andato a buon fine. La preghiamo di 
                        riprovare ad inserire l&apos;immagine attraverso la Modifica del vino.');
                        header('Location: modify_wine.php?idwine=' . $row['id_wine']);
                    }

                } 
                else {
                    setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare');
                    header('Location: add_wine.php');
                }
            }
        }
    } 
    //controllo il caso in cui i campi non sono vuoti ma non è stata inserita l'immagine o è stata inserita un'immagine del formato sbagliato
    else if(!empty($_POST['nome']) && !preg_match('/^(\s)+$/', $_POST['nome']) && !empty($_POST['tipologia']) && 
    !preg_match('/^(\s)+$/', $_POST['tipologia']) && !empty($_POST['descrizione']) && !preg_match('/^(\s)+$/', $_POST['descrizione']) 
    && !empty($_POST['denominazione']) && !preg_match('/^(\s)+$/', $_POST['denominazione']) && !empty($_POST['annata']) 
    && !empty($_POST['vitigno']) && !preg_match('/^(\s)+$/', $_POST['vitigno']) && !empty($_POST['abbinamento']) && 
    !preg_match('/^(\s)+$/', $_POST['abbinamento']) && !empty($_POST['degustazione']) && !preg_match('/^(\s)+$/', $_POST['degustazione']) 
    && !empty($_POST['gradazione']) && !preg_match('/^(\s)+$/', $_POST['gradazione']) && !empty($_POST['formato']) && 
    !preg_match('/^(\s)+$/', $_POST['formato']) && !empty($_FILES['wine_img']) && $_FILES['wine_img']['type'] != 'image/png'){
        setcookie('error', 'Deve essere inserita un&apos;immagine con estensione ".png" .');
        header('Location: add_wine.php');
    }
    else{
        setcookie('error', 'Alcuni campi risultano vuoti.');
        header('Location: add_wine.php');
    }

}

$vino .= '</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $vino, $pagina);

//chiudo la connessione
mysqli_close($conn);
