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
$dati = '';
$info_errore = '';
$error = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info_errore .= "<div>" . $_COOKIE['info'] . "</div>";
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $info_errore .= "<div>" . $_COOKIE['error'] . "</div>";
    setcookie('error', null);
}

//controllo i campi del form (username, nome, email) e verifico che non siano vuoti o che abbiamo spazi
if (!empty($_POST['username']) && !preg_match("/^(\s)+$/", $_POST['username']) && !empty($_POST['nome']) &&
    !preg_match("/^(\s)+$/", $_POST['nome']) && !empty($_POST['email']) && !preg_match("/^(\s)+$/", $_POST['email'])) {

    //dichiarazione variabili
    $username = $_POST['username'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    //controllo che i dati presenti non siano uguali a quelli già presenti nel database
    $sql = "SELECT * FROM utenti WHERE id_user='" . $_SESSION['id'] . "' AND username='" . $username . "' AND nome='" . $nome . "'
    AND email='" . $email . "'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) { //i dati inseriti dall'utente non sono uguali a quelli già presenti nel database, 
        //quindi l'utente vuole cambiare almeno un campo dato

        // controllo che la mail sia del formato giusto
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
            $error .= "L'email inserita non rispetta il formato corretto. Es. example@domain.com\n";
        }

        // controllo se anche i campi password sono settati e non vuoti
        if (!empty($_POST['actual_password']) && !empty($_POST['new_password'])) {

            //dichiarazione varibili
            $current_password = $_POST['actual_password'];
            $new_password = $_POST['new_password'];

            // controllo se la 'actual_password' coincide con la password del database
            $sql = "SELECT * FROM utenti WHERE id_user='" . $_SESSION['id'] . "' AND password=MD5('" . $current_password . "')";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) != 0) { // vuol dire che la password che l'utente ha inserito all'interno della
                //casella 'Password Corrente" corrisponde con quella nel database

                //controllo che la password corrente e quella nuova non siano uguali
                if ($new_password != $current_password) {
                    //se sono diverse allora dovrò salvare la nuova password nel database, se è del formato giusto
                    if (empty($error)) { //se non ci sono errori precedenti procedo
                        //controllo il formato della password
                        if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $_POST['new_password'])) {

                            //posso salvare anche la nuova password nel database
                            $sql = "UPDATE utenti SET nome='" . $nome . "', username='" . $username . "',
                            password=MD5('" . $new_password . "'), email='" . $email . "' WHERE id_user='" . $_SESSION['id'] . "'";

                            $result = mysqli_query($conn, $sql);

                            //se la query è andata a buon fine
                            if ($result) {
                                setcookie('info', "Modifica dati eseguita con successo");
                                header("Location: admin_profile.php");
                            } 
                            else { //se non sono riuscito a cambiare dati nel database
                                $error .= "Si è verificato un errore. La preghiamo di riprovare.\n";
                            }

                        } 
                        else {
                            $error .= "La nuova password non rispetta il giusto formato. Deve essere lunga almeno 8
                        caratteri e contenere almeno una lettera maiuscola, almeno una lettera minuscola ed almeno un
                        numero.\n";
                        }

                    }

                } else { // le password erano uguali quindi cambio solo i dati esclusi la password

                    if (empty($error)) {
                        $sql = "UPDATE utenti SET nome='" . $nome . "', username='" . $username . "', email='" . $email . "' WHERE id_user='" . $_SESSION['id'] . "'";

                        $result = mysqli_query($conn, $sql);

                        if ($result) { // se c'è stata una modifica allora tutto ok
                            setcookie('info', "Modifica dati eseguita con successo");
                            header("Location: admin_profile.php");
                        } else { // se non sono riuscito a cambiare dati nel database
                            $error .= "Si è verificato un errore. La preghiamo di riprovare.\n";

                        }
                    }
                }

            } else {
                $error .= "La password inserita non &egrave; corretta.\n";
            }

        } 
        else { // salvo solo i dati relativi a username, nome ed email

            //nel caso in cui solo un campo password sia stato scritto, setto la variabile $error
            if(!empty($_POST['new_password']) || !empty($_POST['actual_password'])){
                $error .= "Entrambi i campi password devono essere compilati.\n";
            }

            //se la variabile $error è vuota allora procedo all'update nel database
            if (empty($error)) {
                $sql = "UPDATE utenti SET nome='" . $nome . "', username='" . $username . "', email='" . $email . "' WHERE
                id_user='" . $_SESSION['id'] . "'";

                $result = mysqli_query($conn, $sql);

                //se la query è andata a buon fine
                if ($result) {
                    setcookie('info', "Modifica dati eseguita con successo");
                    header("Location: admin_profile.php");
                } 
                else { // se non sono riuscito a cambiare dati nel database
                    $error .= "Si è verificato un errore. La preghiamo di riprovare.\n";

                }

            }
        }

    }
    //nel caso in cui non ci siano modifiche nei dati, controllo solo se i campi password sono settati e non vuoti
    else if (!empty($_POST['actual_password']) && !empty($_POST['new_password'])) {

        //dichiarazione varibili
        $current_password = $_POST['actual_password'];
        $new_password = $_POST['new_password'];

        // controllo se la 'actual_password' coincide con la password del database
        $sql = "SELECT * FROM utenti WHERE id_user='" . $_SESSION['id'] . "' AND password=MD5('" . $current_password . "')";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) != 0) { // vuol dire che la password che l'utente ha inserito all'interno della
            //casella 'Password Corrente" corrisponde con quella nel database

            //controllo che la password corrente e quella nuova non siano uguali
            if ($new_password != $current_password) {
                //se sono diverse allora dovrò salvare la nuova password nel database, se è del formato giusto
                if (empty($error)) { //se non ci sono errori precedenti procedo
                    //controllo il formato della password
                    if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $_POST['new_password'])) {

                        //posso salvare anche la nuova password nel database
                        $sql = "UPDATE utenti SET nome='" . $nome . "', username='" . $username . "',
                        password=MD5('" . $new_password . "'), email='" . $email . "' WHERE id_user='" . $_SESSION['id'] . "'";

                        $result = mysqli_query($conn, $sql);

                        //se la query è andata a buon fine
                        if ($result) {
                            setcookie('info', "Modifica dati eseguita con successo");
                            header("Location: admin_profile.php");
                        } 
                        else { //se non sono riuscito a cambiare dati nel database
                            $error .= "Si è verificato un errore. La preghiamo di riprovare.\n";
                        }

                    } 
                    else {
                        $error .= "La nuova password non rispetta il giusto formato. Deve essere lunga almeno 8
                    caratteri e contenere almeno una lettera maiuscola, almeno una lettera minuscola ed almeno un
                    numero.\n";
                    }

                }

            } 
            else { //le password sono uguali quindi non aggiorno il database
                    $error .= "La nuova password è identica alla precedente.\n";  
                }            
        }
        else {
            $error .= "La password inserita non &egrave; corretta.\n";
        }

    }
    //nel caso in cui non ci siano modifiche nei dati ma l'utente abbia compilato un solo campo password, setto la variabile $error
    else if(!empty($_POST['new_password']) || !empty($_POST['actual_password'])){
            $error .= "Entrambi i campi password devono essere compilati.\n";
        }

} 
else {
    // faccio capire all'utente che ha lasciato dei campi vuoti (esclusi password)
    // oppure ci sono degli spazi all'interno di username, nome o email

    //se è settata $_POST['save_profile'] significa che l'utente ha provato a salvare i dati del form
    if (!empty($_POST['save_profile'])) {
        $error .= "Alcuni campi dati sono stati lasciati vuoti o contengono degli spazi vietati.\n";
    }

}

//se la variabile $error non è vuota, ricarico la pagina per mostrare i messaggi d'errore
if (!empty($error)) {
    setcookie('error', $error);
    header("Location: admin_profile.php");
}

//FORM DATI PROFILO

$sql = "SELECT utenti.* FROM utenti WHERE id_user='" . $_SESSION['id'] . "'";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$dati .= '<h1 id="admin_title">Dati del profilo</h1>';

$dati .= '<form onsubmit="return checkModifyProfile()" id="admin_profile_page" action="admin_profile.php" method="post">';

if (mysqli_num_rows($result) != 0) {
    $dati .= '<ul>
                <li><label>Username</label></li><li><span id="username_error" class="js_error"></span></li><li><input id="username" type="text" maxlength="100" name="username" id=""
                title="username" value="' . $row['username'] . '" onfocusout="checkUsername()"/></li>
                <li><label>Nome</label></li><li><span id="firstname_error" class="js_error"></span></li><li><input id="firstname" type="text" maxlength="100" name="nome" id="" title="nome"
                value="' . $row['nome'] . '" onfocusout="checkUserFirstName()"/></li>
                <li><label>Email</label></li><li><span id="mail_error" class="js_error"></span></li><li><input id="email" type="text" maxlength="100" name="email" id="" title="email"
                value="' . $row['email'] . '" onfocusout="checkEmail()"/></li>
                <li><label>Password attuale*</label></li><li><span id="password_error" class="js_error"></span></li><li><input id="password" type="password" maxlength="100" name="actual_password"
                id="" title="password attuale" value=""/></li>
                <li><label>Password nuova*</label></li><li><span id="new_password_error" class="js_error"></span></li><li><input id="new_password" type="password" maxlength="100" name="new_password" id=""
                title="password nuova" value=""/></li>
            </ul>
                    <label id="required_fields_profile">*Campi obbligatori UNICAMENTE per il cambio password</label>';
    $dati .= '<input type="submit" class="search_button" name="save_profile" id="save_admin_profile" value="Salva" />';
} else {
    $dati .= '<h2>Ci sono stati dei problemi con il database. La preghiamo di ricaricare la pagina.</h2>';
}

$dati .= "</form>";

//creazione della pagina web

//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);

//chiudo la connessione
mysqli_close($conn);
