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
$dati = '';
$user = '';
$annata = '';
$info_errore = '';
$error = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<div>' . $_COOKIE['info'] . '</div>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<div class="error_sentence">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//prendo l'id dell'utente che voglio andare a modificare
if (!empty($_POST['user'])) {
    $id_user = $_POST['user'];
} else if (!empty($_GET['user'])) {
    //per evitare sql injection uso la funzione htmlentities() che converte ogni possibile carattere con l'entità HTML relativa
    $id_user = htmlentities($_GET['user'], ENT_QUOTES);
} else {
    header('Location: admin_users.php');
}

//controllo che sia stata inviata la submit
if (!empty($_POST['save_user'])) {
    //controllo che non siano stati lasciati campi vuoti
    if (!empty($_POST['nome']) && !preg_match('/^(\s)+$/', $_POST['nome']) && !empty($_POST['email']) && !empty($_POST['username']) && !preg_match('/^(\s)+$/', $_POST['username'])) {

        $username = $_POST['username'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        // siccome tutti i campi non sono vuoti allora potrò procedere con i controlli all'interno del database

        $sql = 'SELECT * FROM utenti WHERE id_user="' . $id_user . '" AND username="' . $username . '" AND nome="' . $nome
            . '" AND email="' . $email . '"';

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) { // dati diversi, quindi da cambiare

            // controllo che la mail sia del formato giusto

            if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
                $error .= 'L&apos;email inserita non rispetta il formato corretto.<br />';
            }

            if (!empty($_POST['password'])) {
                // allora cambio anche la password

                if (empty($error)) {
                    $password = $_POST['password'];

                    if (preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', $password)) { // password del formato giusto

                        $sql = 'UPDATE utenti SET nome="' . $nome . '", username="' . $username . '", password=MD5("' . $password
                            . '"), email="' . $email . '" WHERE id_user="' . $id_user . '"';

                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                            setcookie('info', 'Modifica dati eseguita con successo');
                            header('Location: admin_users.php');
                        } else {
                            $error .= 'Si è verificato un errore. La preghiamo di riprovare.<br />';
                        }

                    } else {
                        $error .= 'La nuova password è in un formato sbagliato.<br />';
                    }

                }

            } else { //cambio solo la i dati principali
                if (empty($error)) {

                    $sql = 'UPDATE utenti SET nome="' . $nome . '", username="' . $username . '", email="' . $email . '"
                    WHERE id_user="' . $id_user . '"';

                    $result = mysqli_query($conn, $sql);

                    if ($result) { // se c'è stata una modifica allora tutto ok
                        setcookie('info', 'Modifica dati eseguita con successo');
                        header('Location: admin_users.php');
                    } else { // se non sono riuscito a cambiare dati nel database
                        $error .= 'Si è verificato un errore. La preghiamo di riprovare.<br />';

                    }
                }
            }
        }

    } else {
        $error .= 'Alcuni campi dati sono stati lasciati vuoti o non sono del formato giusto.';
    }

}

if (!empty($error)) {
    setcookie('error', $error);
    header('Location: modify_users.php?user=' . $id_user);
}

//FORM DATI UTENTE
$sql = 'SELECT * FROM utenti WHERE id_user="' . $id_user . '"';

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);

if (mysqli_num_rows($result) != 0) {
    $user .= '<h1 id="admin_title">Modifica utente</h1>
    <form onsubmit="return checkModifyUser()" id="admin_profile_page" action="modify_users.php" method="post">
    <ul>
    <li>
    <input type="hidden" name="user" value="' . $id_user . '" />
    </li>
    <li class="label_add">
        <label>Nome Completo</label>
    </li>
    <li>
        <span id="firstname_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" id="firstname" type="text" maxlength="100" name="nome" title="nome" value="' .
        $row['nome'] . '" onblur="checkUserFirstName()"
        tabindex="7"/>
    </li>

    <li class="label_add">
        <label>Username</label>
    </li>
    <li>
        <span id="username_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" id="username" type="text" maxlength="100" name="username" title="username" value="'
        . $row['username'] . '"
            onblur="checkUsername()"  tabindex="8" />
    </li>


    <li class="label_add">
        <label>Email</label>
    </li>
    <li>
        <span id="mail_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" id="email" type="text" maxlength="100" name="email" title="email" value="' .
        $row['email'] . '" onblur="checkEmail()"
        tabindex="9"/>
    </li>

    <li class="label_add">
        <label>Password</label>
    </li>
    <li>
        <span id="password_error" class="js_error"></span>
    </li>
    <li>
        <input class="input_add" id="password" type="password" maxlength="100" name="password" title="password"
        onblur="checkPasswordPanel()" tabindex="10"
        />
    </li>

    <li class="label_add">
        <label>Conferma Password</label>
    </li>
    <li>
        <span id="confirm_password_error" class="js_error">
         </span>
    </li>
    <li>
        <input class="input_add" id="password_confirmation" type="password" maxlength="100" name="conferma_password"
        title="conferma_password" tabindex="11" onblur="checkPasswordConfirmation()"/>
    </li>

    <li>
    <input type="submit" class="search_button" name="save_user" id="save_admin_profile" value="Salva" tabindex="12"/>
    </li>
    </ul></form>';
} else {
    $user .= '<h2>Non sono state trovate informazioni riguardo l&apos;utente selezionato.</h2>';
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $user, $pagina);

//chiudo la connessione
mysqli_close($conn);
