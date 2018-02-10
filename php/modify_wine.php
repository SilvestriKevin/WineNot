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
$vino = '';
$info_errore = '';
$error = '';

//setto un cookie che mi servirà per tornare a questo form dopo aver inserito una nuova annata
$url = $_SERVER['PHP_SELF'];
if (!empty($_SERVER['QUERY_STRING'])) {
    $url .= '?' . $_SERVER['QUERY_STRING'];
}

setcookie('modifyWine', $url);

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<div class="info_sentence">' . $_COOKIE['info'] . '</div>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<div class="error_sentence">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//prendo l'id del vino che voglio andare a modificare
if (!empty($_POST['idwine'])) {
    $id_wine = $_POST['idwine'];
} else if (!empty($_GET['idwine'])) {
    //per evitare sql injection uso la funzione htmlentities() che converte ogni possibile carattere con l'entità HTML relativa
    $id_wine = htmlentities($_GET['idwine'], ENT_QUOTES);
} else {
    header('Location: admin_wines.php');
}

//controllo che sia stata inviata la submit
if (!empty($_POST['save_wine'])) {
    //controllo che non siano stati lasciati campi vuoti
    if (!empty($_POST['annata']) && !preg_match('/^(\s)+$/', $_POST['annata']) &&
        !empty($_POST['nome']) && !preg_match('/^(\s)+$/', $_POST['nome']) &&
        !empty($_POST['tipologia']) && !preg_match('/^(\s)+$/', $_POST['tipologia']) &&
        !empty($_POST['vitigno']) && !preg_match('/^(\s)+$/', $_POST['vitigno']) &&
        !empty($_POST['denominazione']) && !preg_match('/^(\s)+$/', $_POST['denominazione']) &&
        !empty($_POST['gradazione']) && !preg_match('/^(\s)+$/', $_POST['gradazione']) &&
        !empty($_POST['formato']) && !preg_match('/^(\s)+$/', $_POST['formato']) &&
        !empty($_POST['descrizione']) && !preg_match('/^(\s)+$/', $_POST['descrizione']) &&
        !empty($_POST['abbinamento']) && !preg_match('/^(\s)+$/', $_POST['abbinamento']) &&
        !empty($_POST['degustazione']) && !preg_match('/^(\s)+$/', $_POST['degustazione'])) {

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

        //CONTROLLO DI ALCUNI CAMPI DEL FORM

        //gradazione: il formato consentito è di 1 o 2 interi seguiti dal punto seguito poi da 1 sola cifra decimale
        //es. 7.5  oppure  13.5
        if (!preg_match('/^\d{1,2}\.\d$/', strval($gradazione))) {
            $error .= 'Gradazione non è nel formato corretto (es. 7.5% o 13.0%).<br />';
        }

        //formato: il formato corretto è 1 intero seguito dal punto seguito poi da 2 cifre decimali
        //es. 1.75  oppure  2.00
        if (!preg_match('/^\d\.\d{2}$/', $formato)) {
            $error .= 'Formato non è nel formato corretto (es. 2.00L).<br />';
        }

        //se ho caricato un'immagine, dò la possibilità di poterla cambiare
        if ($_FILES['wine_img']['size']!=0 && $_FILES['wine_img']['type'] == 'image/png') {
            $file = $_FILES['wine_img'];

            //immagine: controllo che sia stata caricata correttamente l'immagine
            if ($file['error'] != UPLOAD_ERR_OK && !is_uploaded_file($file['tmp_name'])) {
                $error .= 'C&apos;&egrave; stato un problema con il caricamento dell&apos;immagine. La preghiamo di riprovare.<br />';
            }
        } 
        //se inserisco un'immagine con estensione diversa da '.png'
        else if($_FILES['wine_img']['size']!=0 && $_FILES['wine_img']['type'] != 'image/png'){
            $error .= 'Deve essere inserita un&apos;immagine con estensione ".png".';
        }
        //controllo se mi trovo nel caso in cui l'inserimento vino è riuscito però l'inserimento immagine è fallito.
        //in questo caso l'utente deve inserire un'immagine
        else if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/WineNot/img/' . $id_wine . '.png') && $_FILES['wine_img']['size']==0){
            $error .= 'Deve essere inserita un&apos;immagine con estensione ".png" 2.';
        }

        //se $error non è vuota allora ricarico la pagina mostrando gli errori rilevati
        if (!empty($error)) {
            setcookie('error', $error);
            header('Location: modify_wine.php?idwine=' . $id_wine);
        } else {
            //controllo che sia stato modificato almeno un campo, altrimenti non serve fare l'update nel database
            $sql = 'SELECT * FROM vini WHERE id_wine="' . $id_wine . '" AND annata="' . $annata . '"
            AND nome="' . htmlentities($nome, ENT_QUOTES) . '" AND tipologia="' . htmlentities($tipologia, ENT_QUOTES) . '" AND
            vitigno="' . htmlentities($vitigno, ENT_QUOTES) . '" AND denominazione="' . htmlentities($denominazione, ENT_QUOTES) .
            '" AND gradazione="' . $gradazione . '" AND formato="' . $formato . '" AND descrizione="' . htmlentities($descrizione, ENT_QUOTES)
            . '" AND abbinamento="' . htmlentities($abbinamento, ENT_QUOTES) . '" AND degustazione="' . htmlentities($degustazione, ENT_QUOTES)
                . '"';

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                //controllo che questo vino modificato non esista già nel database (escludendo il vino prima delle
                //modifiche ovviamente)
                $sql = 'SELECT * FROM (SELECT * FROM vini WHERE id_wine!="' . $id_wine . '") WHERE annata="' . $annata . '"
                AND nome="' . htmlentities($nome, ENT_QUOTES) . '" AND tipologia="' . htmlentities($tipologia, ENT_QUOTES) . '" AND
                denominazione="' . htmlentities($denominazione, ENT_QUOTES) . '"';

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) != 0) {
                    $error .= 'Un altro vino con queste informazioni &egrave; gi&agrave; presente nel database.<br />';
                } else { //aggiorno il vino nel database
                    $sql = 'UPDATE vini SET nome="' . htmlentities($nome, ENT_QUOTES) . '", tipologia="' . htmlentities($tipologia, ENT_QUOTES)
                    . '", descrizione="' . htmlentities($descrizione, ENT_QUOTES) . '", denominazione="' . htmlentities($denominazione, ENT_QUOTES)
                    . '", annata="' . $annata . '", vitigno="' . htmlentities($vitigno, ENT_QUOTES) . '", abbinamento="' .
                    htmlentities($abbinamento, ENT_QUOTES) . '", degustazione="' . htmlentities($degustazione, ENT_QUOTES) .
                    '",gradazione="' . $gradazione . '", formato="' . $formato .'" WHERE id_wine="' . $id_wine . '"';

                    //se la query è stata eseguita con successo
                    if (mysqli_query($conn, $sql)) {
                        //se il cookie è settato, lo unsetto
                        if (isset($_COOKIE['modifyWine'])) {
                            unset($_COOKIE['modifyWine']);
                            setcookie('modifyWine', '', time() - 3600);
                        }
                        //controllo che sia stata inserita un'immagine nuova
                        if(!empty($file)){
                            //assegno il nome che mi serve alla foto (cioè l'id_wine)
                            $file['name'] = $id_wine;

                            //controllo che l'immagine sia stata caricata nella cartella correttamente
                            if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/WineNot/img/' . $file['name'] . '.png')) {
                                setcookie('info', 'Modifica dei dati avvenuta con successo.');
                                //ritorno alla pagina di gestione vini
                                header('Location: admin_wines.php');
                            } else { // il caricamento file non è andato a buon fine, tuttavia la query è stata eseguita
                                $error .= 'Il caricamento dell&apos;immagine non &egrave; andato a buon fine. Tuttavia gli altri
                                dati sono stati aggiornati correttamente.<br />';
                            }
                        } else{
                            setcookie('info', 'Modifica dei dati avvenuta con successo.');
                            //ritorno alla pagina di gestione vini
                            header('Location: admin_wines.php');
                        }
                    } else { // la modifica dati non è andata a buon fine
                        $error .= 'La modifica dei dati non &egrave; andata a buon fine. La preghiamo di riprovare.<br />' . $sql;
                    }
                }
            } //nel caso in cui l'utente abbia cercato di salvare, non avendo però modificato nessun dato, viene mostrato a video
            //il messaggio di 'modifica dati avvenuta con successo' per evitare il caso di metafora visiva
            else {
                setcookie('info', 'Modifica dati avvenuta con successo');
                header('Location: admin_wines.php');
            }
        }

    } else {
        $error .= 'Alcuni campi dati sono stati lasciati vuoti.';
    }
}

//se $error non è vuota allora ricarico la pagina mostrando gli errori rilevati
if (!empty($error)) {
    setcookie('error', $error);
    header('Location: modify_wine.php?idwine=' . $id_wine);
}

//FORM DATI VINO
$sql = 'SELECT * FROM vini WHERE id_wine="' . $id_wine . '"';

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);

//se esiste un vino con questo id procedo
if (mysqli_num_rows($result) != 0) {
    $vino .= '<h1 id="admin_title">Modifica vino</h1>';
    $vino .= '<form onsubmit="return checkModifyWine()" id="panel_admin_form_add_wine" enctype="multipart/form-data"
    action="modify_wine.php" method="post">';
    $vino .= '<ul><li><input type="hidden" name="idwine" value="' . $id_wine . '" /></li>';
    $annata = $row['annata'];

    //aggiungo tutte le annate
    $vino .= '<li><label>Annata</label></li><li><select name="annata" tabindex="7">';
    $sql = 'SELECT anno FROM annate ORDER BY anno';
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) != 0) {
        while ($subrow = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $vino .= '<option value="' . $subrow['anno'] . '"';
            //faccio in modo che venga selezionata l'annata giusta per questo vino
            if ($annata == $subrow['anno']) {
                $vino .= ' selected="selected"';
            }

            $vino .= '>' . $subrow['anno'] . '</option>';
        }
    }

    $vino .= '</select></li><li>
    <a title="Aggiungi annata" href="./add_year.php" tabindex="8" accesskey="a">Aggiungi Annata</a></li>
    <li><input type="hidden" name="action" value="upload" /></li>
    <li class="label_add">
        <label>Nome</label>
    </li>
    <li>
        <span id="wine_name_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" type="text" maxlength="30" name="nome" title="nome" value="' . $row['nome'] . '" onblur="checkNome()" tabindex="9" />
    </li>

    <li class="label_add">
        <label>Tipologia</label>
    </li>
    <li>
        <span id="wine_tipologia_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" type="text" maxlength="30" name="tipologia" title="tipologia" value="' . $row['tipologia'] . '" onblur="checkTipologia()"
        tabindex="10"/>
    </li>

    <li class="label_add">
        <label>Vitigno</label>
    </li>
    <li>
        <span id="wine_vitigno_error" class="js_error"></span>
    </li>
    <li>
        <textarea name="vitigno" title="vitigno" onblur="checkVitigno()" rows="7" cols="34" tabindex="11">' . $row['vitigno'] . '</textarea>
    </li>

    <li class="label_add">
        <label>Denominazione</label>
    </li>
    <li>
        <span id="wine_denominazione_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" type="text" maxlength="30" name="denominazione" title="denominazione" value="' . $row['denominazione'] . '" onblur="checkDenominazione()"
        tabindex="12"/>
    </li>

    <li class="label_add">
        <label>Gradazione(%)</label>
    </li>
    <li>
        <span id="wine_gradazione_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" type="text" maxlength="30" name="gradazione" title="gradazione" value="' . $row['gradazione'] . '" onblur="checkGradazione()"
        tabindex="13"/>
    </li>

    <li class="label_add">
        <label>Formato(l)</label>
    </li>
    <li>
        <span id="wine_formato_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" type="text" maxlength="30" name="formato" title="formato" value="' . $row['formato'] . '" onblur="checkFormato()"
        tabindex="14"/>
    </li>

    <li class="label_add">
        <label>Descrizione</label>
    </li>
    <li>
        <span id="wine_descrizione_error" class="js_error"></span>
    </li>
    <li>
        <textarea name="descrizione" title="descrizione" onblur="checkDescrizione()" rows="7" cols="34" tabindex="15">' . $row['descrizione'] . '
        </textarea>
    </li>

    <li class="label_add">
        <label>Abbinamento</label>
    </li>
    <li>
        <span id="wine_abbinamento_error" class="js_error"></span>
    </li>
    <li>
        <textarea name="abbinamento" title="abbinamento" onblur="checkAbbinamento()" rows="7" cols="34" tabindex="16">' . $row['abbinamento'] . '
        </textarea>
    </li>

    <li class="label_add">
        <label>Degustazione</label>
    </li>
    <li>
        <span id="wine_degustazione_error" class="js_error"></span>
    </li>
    <li>
        <textarea name="degustazione" title="degustazione" onblur="checkDegustazione()" rows="7" cols="34" tabindex="17">' . $row['degustazione'] . '
        </textarea>
    </li>

    <li class="label_add">
        <label>Immagine attuale</label>
    </li>
    <li>
        <img id="modify_wine_img" alt="immagine del vino '.$row["nome"].'" src="../img/';

        //controllo che sia presente l'immagine del vino nel server, altrimenti mostro l'immagine di default
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/WineNot/img/' . $row["id_wine"] . '.png')){
            $vino .= $row["id_wine"];
        } else{
            $vino .= 'default_wine';
        }
        
        $vino .= '.png" />
    </li>

    <li class="label_add">
        <label>Cambia immagine</label>
    </li>
    <li>
        <span id="wine_picture_error" class="js_error"></span>
    </li>
    <li>
        <input id="select_file" type="file" name="wine_img" tabindex="18" />
    </li>

    <li><input type="submit" class="search_button" name="save_wine" id="save_modify_wine" value="Salva" tabindex="19"/></li>
    </ul></form>';
} else {
    $vino .= '<h2>Non sono state trovate informazioni riguardo il vino selezionato.</h2>';
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $vino, $pagina);

//chiudo la connessione
mysqli_close($conn);
