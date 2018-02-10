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
$user = '';
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

//controllo che sia stata inviata la submit
if (!empty($_POST['add_user'])) {
    //controllo che non sia stati lasciati campi vuoti
    if (!empty($_POST['nome']) && !preg_match('/^(\s)+$/', $_POST['nome']) && !empty($_POST['username']) &&
        !preg_match('/^(\s)+$/', $_POST['username']) && !empty($_POST['email']) && !preg_match('/^(\s)+$/', $_POST['email'])
        && !empty($_POST['password']) && !preg_match('/^(\s)+$/', $_POST['password']) && !empty($_POST['conferma_password'])
        && !preg_match('/^(\s)+$/', $_POST['conferma_password'])) {

        //dichiarazione variabili
        $nome = htmlentities($_POST['nome'], ENT_QUOTES);
        $username = htmlentities($_POST['username'], ENT_QUOTES);
        $email = $_POST['email'];
        $password = $_POST['password'];
        $conferma_password = $_POST['conferma_password'];
        $message = '';

        //controllo che la mail rispetti il formato corretto (es. example@dominio.com)
        if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
            $message = 'Email non è nel formato corretto (es. example@dominio.com).<br />';
        }

        //controllo che la password rispetti il formato corretto (es. Esempio1)
        if (!preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', $password)) {
            $message .= 'Password non è nel formato corretto (es. Esempio1).<br />';
        }

        //controllo che le due password coincidano
        if ($password != $conferma_password) {
            $message .= 'Le password non corrispondono.';
        }

        //se non ci sono stati problemi
        if (empty($message)) {

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
                    $sql = 'INSERT INTO utenti (nome, username, password, email) VALUES ("' . $nome . '","' . $username . 
                    '", MD5("' . $password . '"),"' . strtolower($email) . '")';

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

//prendo dal database il valore del campo booleano 'admin' dell'utente
$sql = 'SELECT admin FROM utenti WHERE id_user="' . $_SESSION['id'] . '"';
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);

//controllo che l'utente sia l'admin perchè solo l'admin può aggiungere un nuovo utente
if ($row['admin'] == 1) {
    //FORM INSERIMENTO UTENTE
    $user .= '<h1 id="admin_title">Inserimento utente</h1>
                <form onsubmit="return fullyCheckUser()" id="admin_profile_page" action="add_user.php" method="post">
                    <fieldset>
                    <ul>
                    <li id="important_message_user"><span>Tutti i campi sono obbligatori</span></li>

                    <li class="label_add">
                    <label>Nome Completo</label>
                    </li>
                    <li>
                    <span id="firstname_error" class="js_error"></span>
                    </li>
                    <li>
                    <input class="input_add" id="firstname" type="text" maxlength="50" name="nome" title="nome" tabindex="6"
                    onblur="checkUserFirstName()"/>
                    </li>

                    <li class="label_add">
                    <label>Username</label>
                    </li>
                    <li>
                    <span id="username_error" class="js_error"></span>
                    </li>
                    <li>
                    <input class="input_add" id="username" type="text" maxlength="50" name="username" title="username"
                    tabindex="7" onblur="checkUsername()"/>
                    </li>

                    <li class="label_add">
                    <label>Email</label>
                    </li>
                    <li>
                    <span id="mail_error" class="js_error"></span>
                    </li>
                    <li><input class="input_add" id="email" type="text" maxlength="50" name="email" title="email"
                    tabindex="8" onblur="checkEmail()"/>
                    </li>

                    <li class="label_add">
                    <label>Password</label>
                    </li>
                    <li>
                    <span id="password_error" class="js_error"></span>
                    </li>
                    <li>
                    <input class="input_add" id="password" type="password" maxlength="100" name="password" title="password"
                    tabindex="9" onblur="checkPasswordPanel()"/>
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
                    title="conferma_password" tabindex="10" onblur="checkPasswordConfirmation()"/>
                    </li>

                    <li>
                    <input type="submit" class="search_button" name="add_user" value="Salva" id="save_admin_profile"
                    accesskey="s" tabindex="11"/>
                    </li>
                    </ul>
                </fieldset>
                </form>';
} else {
    $user .= '<h2>Non hai diritti di accesso a questa pagina.</h2>';
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
