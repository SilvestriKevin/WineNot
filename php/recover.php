<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

/*PARTE DELL’INVIO EMAIL. Si controlla che l'email (=user) sia presente nel db. Estraggo quindi id e password dell'utente e li unisco in un'unica stringa ($hash) da passare nel $_GET. La stringa su cui cliccare è inviata per email, come conferma, e rinvia al file “nuova_password.php”. */
$stampa = '';

if (!empty($_COOKIE['error'])) {
    $stampa .= '<div class="error_sentence">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

$errore = 0; //variabile di controllo errori (se rimane a 0 non ci sono errori)

if (isset($_POST['email'])) {
    if (empty($_POST['email'])) {
        $errore = 1;
        setcookie('error', 'Il campo email risulta vuoto.');
        header('Location: recover.php');
    } else {
        $sql = 'select id_user as id, password from utenti where email="' . htmlentities($_POST['email'], ENT_QUOTES) . '"';
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            //l’hash ci servirà per recuperare i dati utente e confermare la richiesta
            //la password nel database si presume criptata, con md5 o altro algoritmo
            //al posto di questi due dati, se ne possono usare altri legati all’utente, purché univoci
            $hash = $row['password'] . '' . $row['id'];
        } else {
            $errore = 1;
            setcookie('error', 'L&apos;email inserita non &egrave; stata trovata nel database.');
            header('Location: recover.php');
        }
    }
    //se non ci sono stati errori, invio l’email all’utente con il link da confermare
    if ($errore == 0) {
        $mail_headers = 'From: info@winenot.it \r\n';

        //intestazioni della mail, dove deve essere definito il mittente (From) ed altri eventuali valori
        //come Cc, Bcc, ReplyTo e X-Mailer
        $mail_headers .= "Reply-To: info@winenot.it \r\n";
        $mail_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        //Aggiungo alle intestazioni della mail la definizione di MIME-Version,
        //Content-type e charset (necessarie per i contenuti in HTML)
        $mail_headers .= "MIME-Version: 1.0\r\n";
        $mail_headers .= "Content-type: text/html; charset=iso-8859-1";

        $mail_oggetto = 'Conferma nuova password utente';
        $mail_corpo = '<html><body>';
        $mail_corpo .= 'Clicca sul <a href="http://localhost/WineNot/php/new_password.php?hash=' . $hash . '">link</a>
        per confermare la nuova password.<br /> Se il link non &egrave; visibile, copia la riga qui sotto e
        incollala sul browser: <br /> http://localhost/WineNot/php/new_password.php?hash=' . $hash . '';
        $mail_corpo .= '</body></html>';

        //invio email
        if (mail($_POST['email'], $mail_oggetto, $mail_corpo, $mail_headers)) {
            $stampa .= '<div class="info_sentence">Email inviata con successo.</div>';
            unset($_POST); //elimino le variabili post, in modo che non appaiano nel form
        } else {
            setcookie('error', 'Si &egrave; verificato un errore. La preghiamo di riprovare.');
            header('Location: recover.php');
        }
    }
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/recover.html');
//rimpiazzo i segnaposto e stampo in output la pagina
echo str_replace('[ERRORE]', $stampa, $pagina);

//chiudo la connessione
mysqli_close($conn);
