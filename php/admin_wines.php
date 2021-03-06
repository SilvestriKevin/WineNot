<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//inclusione file per funzioni ausiliarie
include_once '../include/lib.php';

//controllo se è settata la session, altrimenti si viene riportati alla pagina iniziale
if (!isset($_SESSION['id'])) {
    header('Location: ../index.html');
}

//dichiarazione variabili
$dati = '';
$info_errore = '';
$annata = '';
$tipologia = '';
$ordine = '';
$improved_search = '';
$salva_sql = false;

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<div id="top_message">' . $_COOKIE['info'] . '</div>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<div id="top_message">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//se è stato cliccato 'Elimina selezionati'
if (isset($_POST['delete_selected'])) {

    $wines = isset($_POST['wines']) ? $_POST['wines'] : array();

    //se non sono stati selezionati vini stampo un messaggio d'errore
    if (!count($wines)) {
        $info_errore .= '<div id="top_message">Selezionare almeno un vino</div>';
    } else {
        //per poter passare e poter usare un array tramite url posso ricorrere a due metodi:  
        // serialize/unserialize o l'utilizzo di http_build_query che crea un url molto più lungo perchè inserisce ogni
        // elemento singolarmente in questo modo key[indice]=valore
        header('Location: delete_wine.php?wines=' . serialize($wines));
    }
}

//SELECT ANNATA NEL FORM
$sql = 'SELECT annata FROM vini GROUP BY annata ORDER BY annata';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $annata .= '<option value="' . $row['annata'] . '"';
        if (!empty($_POST['annata']) && $_POST['annata'] == $row['annata']) {
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
    if (!empty($_POST['tipologia']) && entityAccentedVowels($_POST['tipologia']) == $array_tipologie[$i]) {
        $tipologia .= ' selected="selected"';
    }

    $tipologia .= '>' . $array_tipologie[$i] . '</option>';
}

//SELECT ORDINE NEL FORM
$array_ordine = array('nome', 'denominazione', 'tipologia', 'annata');
$num_elementi = count($array_ordine);
for ($i = 0; $i < $num_elementi; $i++) {
    $ordine .= '<option value="' . $array_ordine[$i] . '"';
    if (!empty($_POST['ordine']) && $_POST['ordine'] == $array_ordine[$i]) {
        $ordine .= ' selected="selected"';
    }

    $ordine .= '>' . $array_ordine[$i] . '</option>';
}

$text_search = 'vini';

//CREA LA QUERY SECONDO I PARAMETRI DI RICERCA
if (!empty($_POST['annata']) && !empty($_POST['tipologia']) && !empty($_POST['ordine'])) {

    if (!empty($_POST['search'])) {
        //chiamo la funzione in lib.php che controlla il testo inserito e pulisce la stringa
        $search = cleanInput($_POST['search']);

        $counter = 0;

        while (!empty($search[$counter])) {

            if ($counter > 0) {
                $text_search = '( SELECT vini.* FROM ' . $text_search . ' WHERE ( vini.nome LIKE "%' . $search[$counter] .
                 '%" OR vini.denominazione LIKE "%' . $search[$counter] . '%" OR vini.tipologia LIKE "%' . $search[$counter] . 
                 '%" OR vini.annata LIKE "%' . $search[$counter] . '%" ) ) AS vini';
            } else {
                $text_search = '( SELECT vini.* FROM vini WHERE ( vini.nome LIKE "%' . $search[$counter] . 
                '%" OR vini.denominazione LIKE "%' . $search[$counter] . '%" OR vini.tipologia LIKE "%' . 
                $search[$counter] . '%" OR vini.annata LIKE "%' . $search[$counter] . '%" ) ) AS vini';
            }

            $counter++;

        }
    }

    if ($_POST['annata'] != 'All') {
        $improved_search .= ' WHERE annata="' . $_POST['annata'] . '"';
    }

    if ($_POST['tipologia'] != 'All') {
        if (!empty($improved_search)) {
            $improved_search .= ' AND tipologia="' . entityAccentedVowels($_POST['tipologia']) . '"';
        } else {
            $improved_search .= ' WHERE tipologia="' . entityAccentedVowels($_POST['tipologia']) . '"';
        }
    }

    //STAMPA I VINI
    $sql = 'SELECT vini.* FROM ' . $text_search . $improved_search . ' ORDER BY ' . $_POST['ordine'];
    $salva_sql = true;

}
//STAMPA TUTTI I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
else {
    $sql = 'SELECT vini.* FROM vini';
}

//se è stata salvata in precedenza la query, continuo a preservare la query risettando a true $salva_sql e assegno la query a $sql per eseguirla
if (!empty($_POST['sql'])) {
    $salva_sql = true;
    $sql = $_POST['sql'];
}

$result = mysqli_query($conn, $sql);

$dati .= '<form onsubmit="return deleteSelected()" action="admin_wines.php" method="post">';

//se è stata salvata in precedenza la query, allora mantengo i dati della query e della ricerca (annata, tipologia, ordine)
if (!empty($salva_sql)) {
    $dati .= '<input type="hidden" name="sql" value="' . htmlentities($sql, ENT_QUOTES) . '" />';
    $dati .= '<input type="hidden" name="annata" value="' . $_POST['annata'] . '" />';
    $dati .= '<input type="hidden" name="tipologia" value="' . $_POST['tipologia'] . '" />';
    $dati .= '<input type="hidden" name="ordine" value="' . $_POST['ordine'] . '" />';
    if (!empty($_POST['search'])) {
        //utilizzo la funzione htmlentities per ricaricare sul valore search l'input testuale corretto
        $dati .= '<input type="hidden" name="search" value="' . htmlentities($_POST['search'], ENT_QUOTES) . '" />';
        $dati .= '<div>Hai cercato: "' . htmlentities($_POST['search'], ENT_QUOTES) . '"</div>';
    }
}

$dati .= '<div class="hide_content"><div class="select_admin_buttons">
<input type="submit" class="admin_button all_selected" name="all_selected" value="Seleziona Tutti" tabindex="11"/>';
$dati .= '<input type="submit" class="admin_button none_selected" name="none_selected" value="Deseleziona Tutti" tabindex="12"/>';
$dati .= '<input type="submit" class="admin_button delete_selected" name="delete_selected" value="Elimina Selezionati" tabindex="13" />';
$dati .= '<a title="Aggiungi vino" href="./add_wine.php" tabindex="14">Aggiungi Vino</a></div></div>';


$dati .= '<div class="select_admin_buttons hide_js">
<input type="button" class="admin_button all_selected" name="all_selected" value="Seleziona Tutti" onclick="checkThemAll()" tabindex="11"/>
<input type="button" class="admin_button none_selected" name="none_selected" value="Deseleziona Tutti" onclick="uncheckThemAll()" tabindex="12"/>
<input type="submit" class="admin_button delete_selected" name="delete_selected" value="Elimina Selezionati" tabindex="13"/>
<a title="Aggiungi vino" href="./add_wine.php" tabindex="14">Aggiungi Vino</a>
</div>';

if (mysqli_num_rows($result) != 0) {
    $dati .= '<div class="admin_tr" id="admin_header">
                            <div id="menu_select" class="admin_td">Selezione</div>
                            <div class="admin_td">Nome</div>
                            <div class="admin_td">Denominazione</div>
                            <div class="admin_td">Tipologia</div>
                            <div class="admin_td">Annata</div>
                            <div class="admin_td modify_column">Modifica</div>
                            <div class="admin_td remove_column">Elimina</div>
                </div>';

    $counter_index = 15;            
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $dati .= '<div class="admin_tr">';
        $dati .= '<div class ="admin_td admin_wines_checkbox_column"><input class="admin_wines_checkbox admin_checkboxes" 
        type="checkbox" name="wines[]" value="' . $row['id_wine'];
        if (isset($_POST['all_selected'])) {
            $dati .= '" checked="checked';
        }

        $dati .= '" onclick="removeErrorMessage()" tabindex="'. $counter_index++.'"/></div>';
        $dati .= '<div class ="admin_td admin_wines_name_column">' . $row['nome'] . '</div>';
        $dati .= '<div class ="admin_td admin_wines_denomination_column">' . $row['denominazione'] . '</div>';
        $dati .= '<div class ="admin_td admin_wines_tipology_column">' . $row['tipologia'] . '</div>';
        $dati .= '<div class ="admin_td admin_wines_year_column">' . $row['annata'] . '</div>';
        $dati .= '<div class ="admin_td admin_wines_modify_column"><a title="Modifica vino"
        href="./modify_wine.php?idwine=' . $row['id_wine'] . '" tabindex="'. $counter_index++.'">Modifica</a></div>';
        $dati .= '<div class ="admin_td admin_wines_remove_column"><a title="Elimina vino" href="./delete_wine.php?wines=' . $row['id_wine'] . '" tabindex="'. $counter_index++.'">X</a></div>';
        $dati .= '</div>';
    }
} else {
    $dati .= '<h2 id="no_elements">Non sono presenti vini.</h2>';
}

$dati .= '</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');
$search_wine = file_get_contents('../html/search_wine.html');

//tolgo il link della pagina
$pagina = str_replace('<a title="gestione vini" href="admin_wines.php" tabindex="3" accesskey="v">Gestione Vini</a>', 
'Gestione Vini', $pagina);

//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', $search_wine, $pagina);
$pagina = str_replace('[ANNATA]', $annata, $pagina);
$pagina = str_replace('[TIPOLOGIA]', $tipologia, $pagina);
$pagina = str_replace('[ORDINE]', $ordine, $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $dati, $pagina);

//chiudo la connessione
mysqli_close($conn);
