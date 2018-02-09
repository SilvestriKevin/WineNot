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
    //controllo i campi del form (nome completo, username, email) e verifico che non siano vuoti
    if (!empty($_POST['username']) && !preg_match('/^(\s)+$/', $_POST['username']) && !empty($_POST['nome']) &&
        !preg_match('/^(\s)+$/', $_POST['nome']) && !empty($_POST['email']) && !preg_match('/^(\s)+$/', $_POST['email'])) {

        //dichiarazione variabili
        $username = $_POST['username'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        //controllo che i dati non siano uguali a quelli già presenti nel database
        $sql = 'SELECT * FROM utenti WHERE id_user="' . $id_user . '" AND username="' . $username . '" AND nome="' . $nome . '"
        AND email="' . $email . '"';

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) { //i dati inseriti dall'utente non sono uguali a quelli già presenti nel database,
            //quindi l'utente vuole modificare almeno un campo dato

            //controllo che la mail sia del formato corretto
            if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
                $error .= 'L&apos;email inserita non rispetta il formato corretto (es. example@domain.com).<br />';
            }

            //controllo che l'username inserito non sia già presente nel database
            $sql = 'SELECT username FROM (SELECT username FROM utenti WHERE id_user!=' . $id_user . ') AS users WHERE
            username="' . $username . '"';
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) {
                $error .= 'L&apos;username inserito &egrave; gi&agrave; presente nel database.';
            } else {
                //controllo che la email inserita non sia già presente nel database
                $sql = 'SELECT email FROM (SELECT email FROM utenti WHERE id_user!=' . $id_user . ') AS users WHERE
                email="' . $email . '"';
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) != 0) {
                    $error .= 'L&apos;email inserita &egrave; gi&agrave; presente nel database.';
                } else {
                    //controllo se anche i campi password sono settati e non vuoti
                    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {

                        //dichiarazione varibili
                        $new_password = $_POST['new_password'];
                        $confirm_password = $_POST['confirm_password'];

                        //controllo che la nuova password e quella di conferma siano uguali
                        if ($confirm_password == $new_password) {
                            //se non ci sono errori precedenti procedo
                            if (empty($error)) {
                                //controllo il formato della nuova password
                                if (preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', $new_password)) {

                                    //posso salvare anche la nuova password nel database
                                    $sql = 'UPDATE utenti SET nome="' . $nome . '", username="' . $username . '",
                            password=MD5("' . $new_password . '"), email="' . $email . '" WHERE id_user="' . $id_user . '"';

                                    $result = mysqli_query($conn, $sql);

                                    //se la query è andata a buon fine
                                    if ($result) {
                                        setcookie('info', 'Modifica dati avvenuta con successo');
                                        header('Location: admin_users.php');
                                    } else { //se non sono riuscito a cambiare dati nel database
                                        $error = 'Si &egrave; verificato un errore. La preghiamo di riprovare.<br />';
                                    }

                                } else {
                                    $error = 'La nuova password non rispetta il giusto formato. Deve essere lunga almeno 8
                        caratteri e contenere almeno una lettera maiuscola, almeno una lettera minuscola ed almeno un
                        numero.<br />';
                                }

                            }

                        } else {
                            $error .= 'Le password non corrispondono.<br />';
                        }

                    } else { // salvo solo i dati relativi a nome completo, username ed email

                        //nel caso in cui solo un campo password sia stato scritto, setto la variabile $error
                        if (!empty($_POST['confirm_password']) || !empty($_POST['new_password'])) {
                            $error .= 'Entrambi i campi password devono essere compilati.<br />';
                        }

                        //se la variabile $error è vuota allora procedo all'update nel database
                        if (empty($error)) {
                            $sql = 'UPDATE utenti SET nome="' . $nome . '", username="' . $username . '", email="' . $email . '" WHERE
                        id_user="' . $id_user . '"';

                            $result = mysqli_query($conn, $sql);

                            //se la query è andata a buon fine
                            if ($result) {
                                setcookie('info', 'Modifica dati avvenuta con successo');
                                header('Location: admin_users.php');
                            } else { // se non sono riuscito a cambiare dati nel database
                                $error .= 'Si &egrave; verificato un errore. La preghiamo di riprovare.<br />';

                            }

                        }
                    }
                }
            }
        }
        //nel caso in cui non ci siano modifiche nei dati, controllo solo se i campi password sono settati e non vuoti
        else if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {

            //dichiarazione varibili
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            //controllo che la nuova password e quella di conferma siano uguali
            if ($confirm_password == $new_password) {
                //controllo il formato della nuova password
                if (preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', $new_password)) {

                    //posso salvare anche la nuova password nel database
                    $sql = 'UPDATE utenti SET nome="' . $nome . '", username="' . $username . '",
                        password=MD5("' . $new_password . '"), email="' . $email . '" WHERE id_user="' . $id_user . '"';

                    $result = mysqli_query($conn, $sql);

                    //se la query è andata a buon fine
                    if ($result) {
                        setcookie('info', 'Modifica dati avvenuta con successo');
                        header('Location: admin_users.php');
                    } else { //se non sono riuscito a cambiare dati nel database
                        $error .= 'Si &egrave; verificato un errore. La preghiamo di riprovare.<br />';
                    }

                } else {
                    $error .= 'La nuova password non rispetta il giusto formato. Deve essere lunga almeno 8
                    caratteri e contenere almeno una lettera maiuscola, almeno una lettera minuscola ed almeno un
                    numero.<br />';
                }

            } else { //le password sono uguali quindi non aggiorno il database
                $error .= 'Le password non corrispondono.<br />';
            }

        }
        //nel caso in cui non ci siano modifiche nei dati ma l'utente abbia compilato un solo campo password, setto la variabile $error
        else if (!empty($_POST['confirm_password']) || !empty($_POST['new_password'])) {
            $error .= 'Entrambi i campi password devono essere compilati.<br />';
        }
        //nel caso in cui l'utente abbia cercato di salvare, non avendo però modificato nessun dato, viene mostrato a video
        //il messaggio di 'modifica dati avvenuta con successo' per evitare il caso di metafora visiva
        else {
            setcookie('info', 'Modifica dati avvenuta con successo');
            header('Location: admin_users.php');
        }

    } else {
        $error .= 'Alcuni campi risultano vuoti.<br />';
    }
}

//se la variabile $error non è vuota, ricarico la pagina per mostrare i messaggi d'errore
if (!empty($error)) {
    setcookie('error', $error);
    header('Location: modify_user.php?user=' . $id_user);
}

//prendo dal database il valore del campo booleano 'admin' dell'utente
$sql = 'SELECT admin FROM utenti WHERE id_user="' . $_SESSION['id'] . '"';
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);

//controllo che l'utente sia l'admin perchè solo l'admin può modificare un utente
if ($row['admin'] == 1) {
    //controllo che l'amministratore non abbia modificato l'url con il proprio id e nel caso lo invito ad usare
    //l'apposita sezione 'dati profilo'
    if ($id_user != 1) {
        //FORM DATI UTENTE
        $sql = 'SELECT * FROM utenti WHERE id_user="' . $id_user . '"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);

        //se esiste un utente con questo id procedo
        if (mysqli_num_rows($result) != 0) {
            $user .= '<h1 id="admin_title">Modifica utente</h1>
        <form onsubmit="return checkModifyUser()" id="admin_profile_page" action="modify_user.php" method="post">
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
        <label>Password nuova*</label>
        </li>
        <li>
        <span id="password_error" class="js_error"></span>
        </li>
        <li>
        <input class="input_add" id="password" type="password" maxlength="100" name="new_password" title="nuova password"
         tabindex="10"
        />
        </li>

        <li class="label_add">
        <label>Conferma Password*</label>
        </li>
        <li>
        <span id="confirm_password_error" class="js_error">
         </span>
        </li>
        <li>
        <input class="input_add" id="password_confirmation" type="password" maxlength="100" name="confirm_password"
        title="conferma password" tabindex="11"/>
        </li>

        <li>
        <input type="submit" class="search_button" name="save_user" id="save_admin_profile" value="Salva" tabindex="12"/>
        </li>
        <li><span id="required_fields_profile">*Campi obbligatori UNICAMENTE per il cambio password</span></li>
        </ul></form>';
        } else {
            $user .= '<h2>Non sono state trovate informazioni riguardo l&apos;utente selezionato.</h2>';
        }
    } else {
        $user .= '<h2>Per modificare i propri dati utilizzare l&apos;apposita sezione "dati profilo".</h2>';
    }
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
