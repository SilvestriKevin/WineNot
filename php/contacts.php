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

        //controllo che la password e la mail inseriti rispettino le policy
        if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $_POST['email'])) {
            

        } 
        if(empty($error)) {
            $email = 'info@winenot.it';
            $header = 'From: ' . $_POST['email'] . '><br />';
            $header .= 'Content-Type: text/html; charset=\'iso-8859-1\'<br />';
            $header .= 'Content-Transfer-Encoding: 7bit<br /><br />';
            $subject = 'WineNot.it - ';
            $subject .= $_POST['object'];
            $mess_invio = '<html><body>';
            $mess_invio .= $_POST['msg'];
            $mess_invio .= '</body></html>';

            //invio email
            if (mail($email, $subject, $mess_invio, $header)) {
                $info .= '<h1 id="info_message">Email inviata con successo. Grazie per averci contattato!</h1>';
                unset($_POST); //elimino le variabili post
            }
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
