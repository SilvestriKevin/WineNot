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
$user = '';
$info_errore = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= '<div>' . $_COOKIE['info'] . '</div>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= '<div id="error_admin_message">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//prendo dal database il valore del campo booleano 'admin' dell'utente
$sql = 'SELECT admin FROM utenti WHERE id_user="' . $_SESSION['id'] . '"';
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);

//controllo che l'utente sia l'admin perchè solo l'admin può aggiungere un nuovo utente
if ($row['admin'] == 1) {
    //FORM INSERIMENTO UTENTE
    $user .= '<h1 id="admin_title">Inserisci un nuovo utente</h1>
                <form onsubmit="return fullyCheckUser()" id="admin_profile_page" action="add_user.php" method="post">
                    <fieldset>
                    <ul>
                    <li id="important_message_user"><span>Tutti i campi sono obbligatori</span></li>

                    <li><label>Nome Completo</label></li>
                    <li><span id="firstname_error" class="js_error"></span>
                    <li><input id="firstname" type="text" maxlength="50" name="nome" id="nome" title="nome" tabindex="1"
                    onfocusout="checkUserFirstName()"/></li>

                    <li><label>Username</label></li>
                    <li><span id="username_error" class="js_error"></span>
                    <li><input id="username" type="text" maxlength="50" name="username" id="username" title="username"
                    tabindex="1" onfocusout="checkUsername()"/>

                    </li><li><label>Indirizzo email</label>
                    <li><span id="mail_error" id="mail_error" class="js_error"></span>
                    </li><li><input id="email" type="text" maxlength="50" name="email" id="email" title="email"
                    tabindex="5" onfocusout="checkEmail()"/>

                    </li><li><label>Password</label>
                    <li><span id="password_error" class="js_error"></span>
                    </li><li><input id="password" type="password" maxlength="100" name="password" id="password_user"
                    title="password" tabindex="6"onfocusout=" checkPasswordPanel()"/>

                    </li><li><label>Conferma Password</label>
                    <li><span id="confirm_password_error" class="js_error"></span>
                    </li><li><input id="password_confirmation" type="password" maxlength="100" name="conferma_password"
                    id="conferma_password" title="conferma_password" tabindex="7" onfocusout="checkPasswordConfirmation()"/></li>

                    </ul>
                    <input type="submit" class="search_button" name="register" value="Salva" id="save_admin_profile"
                    accesskey="s" tabindex="8"/>

                </fieldset>
                </form>';
} else {
    $user .= '<h2>Non hai diritti di accesso a questa pagina.</h2>';
}

//controllo che i campi del form siano stati settati
if (isset($_POST['nome']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) &&
    isset($_POST['conferma_password'])) {

    //controllo che non sia stati lasciati campi vuoti
    if (!empty($_POST['nome']) && !preg_match('/^(\s)+$/', $_POST['nome']) && !empty($_POST['username']) &&
        !preg_match('/^(\s)+$/', $_POST['username']) && !empty($_POST['email']) && !preg_match('/^(\s)+$/', $_POST['email'])
        && !empty($_POST['password']) && !preg_match('/^(\s)+$/', $_POST['password']) && !empty($_POST['conferma_password'])
        && !preg_match('/^(\s)+$/', $_POST['conferma_password'])) {

        //dichiarazione variabili
        $nome = $_POST['nome'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $conferma_password = $_POST['conferma_password'];
        $message = '';

        //controllo che la mail rispetti il formato corretto (es. example@dominio.com)
        if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
            $message = 'Indirizzo email non è nel formato corretto (es. example@dominio.com).<br />';
        }

        //controllo che la password rispetti il formato corretto (es. Esempio1)
        if (!preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', $password)) {
            $message .= 'Password non è nel formato corretto (es. Esempio1).<br />';
        }

        //controllo che le due password coincidano
        if ($password != $conferma_password) {
            $message .= 'Le password non corrispondono.';
        }

        if (empty($message)) { // se non ci sono stati problemi

            //controllo che l'username inserito non sia già presente nel database
            $sql = 'SELECT username FROM utenti WHERE username="' . $username . '"';
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
                setcookie('error', 'L&apos;username inserito &egrave; gi&agrave; presente nel database.');
                header('Location: add_user.php');
            } else {

                //controllo che la email inserita non sia già presente nel database
                $sql = 'SELECT email FROM utenti WHERE email="' . $email . '"';
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) != 0) {
                    setcookie('error', 'L&apos;email inserita &egrave; gi&agrave; presente nel database.');
                    header('Location: add_user.php');
                } else {
                    // inserisco i dati nel database
                    $sql = 'INSERT INTO utenti (nome, username, password, email) VALUES ("' . $nome . '","' . $username . '",
                MD5("' . $password . '"),"' . strtolower($email) . '")';

                    //controllo la connessione
                    if (mysqli_query($conn, $sql)) {
                        setcookie('info', 'Utente inserito con successo.');
                        header('Location: admin_users.php');
                    } else {
                        setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare');
                        header('Location: add_user.php');
                    }
                }
            }
        } else {
            setcookie('error', $message);
            header('Location: add_user.php');
        }
    } else {
        setcookie('error', 'Alcuni campi risultano vuoti');
        header('Location: add_user.php');
    }
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
