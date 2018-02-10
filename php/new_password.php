<?php
//inclusione file di connessione
include_once '../include/config.php';

//inclusione file per funzioni ausiliarie
include_once '../include/lib.php';

//il controllo del get evita errori di pagina 
if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
    $id = substr($hash, 32);
    $password_old = substr($hash, 0, 32);

    //se fallisce la funzione substr, ritorna FALSE
    if (!$id || !$password_old) {
        echo 'Si &egrave; verificato un errore. La preghiamo di riprovare.';
    } else {

        //nuova password di 8 caratteri
        $password = random(8);
        //controllo che i valori dell’hash corrispondano ai valori salvati nel database
        $sql = 'SELECT * FROM utenti WHERE id_user=' . $id . ' AND password="' . $password_old . '"';
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) != 0) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $email = $row['email'];
            //salvo la nuova password al posto della vecchia (in md5)
            $sql = 'update utenti set password="' . md5($password) . '" where id_user=' . $id . ' and password="' . $password_old . '"';
            $result = mysqli_query($conn, $sql);
            $mail_headers = 'From: info@winenot.it \r\n';

            //intestazioni della mail, dove deve essere definito il mittente (From) ed altri eventuali valori
            //come Cc, Bcc, ReplyTo e X-Mailer
            $mail_headers .= "Reply-To: info@winenot.it \r\n";
            $mail_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

            //Aggiungo alle intestazioni della mail la definizione di MIME-Version,
            //Content-type e charset (necessarie per i contenuti in HTML)
            $mail_headers .= "MIME-Version: 1.0\r\n";
            $mail_headers .= "Content-type: text/html; charset=iso-8859-1";
            $mail_oggetto = 'Nuova password utente';
            $mail_corpo = '<html><body>';
            $mail_corpo .= 'La nuova password utente &egrave; ' . $password . '.<br />Ora puoi accedere tramite 
            <a href="http://localhost/WineNot/php/login.php">Login</a>.<br /> Per una questione di sicurezza, consigliamo di 
            cambiare la password con una a tua scelta tramite la sezione Dati Profilo.';
            $mail_corpo .= '</body></html>';

            if (mail($email, $mail_oggetto, $mail_corpo, $mail_headers)) {
                echo 'La password &egrave; stata cambiata con successo. Controlla la tua email.';
            } else {
                echo 'Si &egrave; verificato un errore. La preghiamo di riprovare.';
            }
        } else {
            echo 'Si &egrave; verificato un errore. La preghiamo di riprovare.';
        }
    }
}
