<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//dichiarazione variabili
$error = '';
$info = '';

//stampo i messaggi informativi e/o di errore
if (!empty($_COOKIE['info'])) {
    $info .= '<div class="info_sentence">' . $_COOKIE['info'] . '</div>';
    setcookie('info', null);
}
if (!empty($_COOKIE['error'])) {
    $error .= '<div class="error_sentence">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//controllo che sia stata inviata la submit
if (!empty($_POST['send_message'])) {

    //controllo che non siano stati lasciati campi vuoti
    if (!empty($_POST['email']) && !preg_match('/^(\s)+$/', $_POST['email']) && !empty($_POST['object']) &&
        !preg_match('/^(\s)+$/', $_POST['object']) && !empty($_POST['msg']) && !preg_match('/^(\s)+$/', $_POST['msg'])) {

        //dichiarazione variabili
        $email = $_POST['email'];
        $object = $_POST['object'];
        $msg = $_POST['msg'];

        //controllo che la mail rispetti il formato corretto (es. example@dominio.com)
        if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
            $error = 'Email non è nel formato corretto (es. example@dominio.com).<br />';
        }

        //se non ci sono stati problemi
        if (empty($error)) {
            $mail_destinatario = 'info@winenot.it';

            //intestazioni della mail, dove deve essere definito il mittente (From) ed altri eventuali valori
            //come Cc, Bcc, ReplyTo e X-Mailer
            $mail_headers = 'From: ' . $email . '>\r\n';
            $mail_headers .= 'Reply-To: ' . $email . '\r\n';
            $mail_headers .= 'X-Mailer: PHP/' . phpversion();
            
            $mail_oggetto = $_POST['object'];
            $mail_corpo = $_POST['msg'];

            //invio email
            if (mail($mail_destinatario, $mail_oggetto, $mail_corpo, $mail_headers)) {
                $info = '<div class="info_sentence">Richiesta inviata con successo. Grazie per averci contattato.</div>';
                unset($_POST); //elimino le variabili post
            } else {
                setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare.');
                header('Location: contacts.php');
            }
        } else {
            setcookie('error', $error);
            header('Location: contacts.php');
        }

    } else {
        setcookie('error', 'Alcuni campi risultano vuoti.');
        header('Location: contacts.php');
    }
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/contacts.html');
//rimpiazzo i segnaposto e stampo in output la pagina
$pagina = str_replace('[ERRORE]', $error, $pagina);
echo str_replace('[INFO]', $info, $pagina);

//chiudo la connessione
mysqli_close($conn);
