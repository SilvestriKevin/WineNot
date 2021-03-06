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

//prendo dal database il valore del campo booleano 'admin' dell'utente
$sql = 'SELECT admin FROM utenti WHERE id_user="' . $_SESSION['id'] . '"';
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

//controllo che l'utente sia l'admin perchè solo l'admin può visualizzare gli utenti
if ($row['admin'] == 1) {
    //se è stato cliccato 'Elimina selezionate'
    if (isset($_POST['delete_selected'])) {

        $users = isset($_POST['users']) ? $_POST['users'] : array();

        //se non sono stati selezionati utenti stampo un messaggio d'errore
        if (!count($users)) {
            $info_errore .= '<div id="top_message">Selezionare almeno un utente</div>';
        } else {
            //per poter passare e poter usare un array tramite url posso ricorrere a due metodi:  serialize/unserialize 
            //o l'utilizzo di http_build_query che crea un url molto più lungo perchè inserisce ogni elemento singolarmente
            //in questo modo key[indice]=valore
            header('Location: delete_user.php?users=' . serialize($users));
        }
    }

    $dati .= '<form onsubmit="return deleteSelected()" action="admin_users.php" method="post">';

    $dati .= '<div class="hide_content"><div class="select_admin_buttons"><input type="submit" class="admin_button all_selected"
            name="all_selected" value="Seleziona Tutti" tabindex="11"/>';
    $dati .= '<input type="submit" class="admin_button none_selected" name="none_selected"  value="Deseleziona Tutti" tabindex="12"/>';
    $dati .= '<input type="submit" class="admin_button delete_selected" name="delete_selected"  value="Elimina Selezionati" tabindex="13"/>';
    $dati .= '<a title="Aggiungi utente" href="./add_user.php" tabindex="14">Aggiungi Utente</a></div></div>';

    $dati .= '<div class="select_admin_buttons hide_js"><input type="button" class="admin_button all_selected" name="all_selected"
             value="Seleziona Tutti" onclick="checkThemAll()" tabindex="11"/>';
    $dati .= '<input type="button" class="admin_button none_selected" name="none_selected" value="Deseleziona Tutti" 
            onclick="uncheckThemAll()" tabindex="12"/>';
    $dati .= '<input type="submit" class="admin_button delete_selected" name="delete_selected" value="Elimina Selezionati" tabindex="13"/>';
    $dati .= '<a title="Aggiungi utente" href="./add_user.php" tabindex="14">Aggiungi Utente</a></div>';

    //STAMPA GLI UTENTI
    $sql = 'SELECT utenti.* FROM utenti WHERE admin=0';
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) != 0) {
        $dati .= '<div class="admin_tr" id="admin_header">
                            <div id="menu_select" class="admin_td">Selezione</div>
                            <div class="admin_td">Nome Completo</div>
                            <div class="admin_td">Username</div>
                            <div class="admin_td">Email</div>
                            <div class="admin_td modify_column">Modifica</div>
                            <div class="admin_td remove_column">Elimina</div>

                    </div>';
        $counter_index = 15;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dati .= '<div class="admin_tr">';
            $dati .= '<div class ="admin_td admin_users_checkbox_column"><input class="admin_users_checkbox admin_checkboxes" 
            type="checkbox" name="users[]" value="' . $row["id_user"];
            if (isset($_POST['all_selected'])) {
                $dati .= '" checked="checked';
            }

            $dati .= '" onclick="removeErrorMessage()" tabindex="' . $counter_index++ . '"/></div>';
            $dati .= '<div class ="admin_td admin_users_name_column">' . $row['nome'] . '</div>';
            $dati .= '<div class ="admin_td admin_users_username_column">' . $row['username'] . '</div>';
            $dati .= '<div class ="admin_td admin_users_email_column">' . $row['email'] . '</div>';
            $dati .= '<div class ="admin_td admin_users_modify_column"><a title="Modifica utente" href="./modify_user.php?user=' 
            . $row['id_user'] . '" tabindex="' . $counter_index++ . '">Modifica</a></div>';
            $dati .= '<div class ="admin_td admin_users_remove_column"><a title="Elimina utente" href="./delete_user.php?users=' 
            . $row['id_user'] . '" tabindex="' . $counter_index++ . '">X</a></div>';
            $dati .= '</div>';
        }
    } else {
        $dati .= '<h2 id="no_elements">Non sono presenti utenti.</h2>';
    }

    $dati .= '</form>';

} else {
    $dati .= '<h2 id="error_admin_users">Non hai diritti di accesso a questa sezione.</h2>';
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');

//tolgo il link della pagina
$pagina = str_replace('<a title="gestione utenti" href="admin_users.php" tabindex="5" accesskey="u">Gestione Utenti</a>', 
'Gestione Utenti', $pagina);

//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $dati, $pagina);

//chiudo la connessione
mysqli_close($conn);
