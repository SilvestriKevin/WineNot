<?php
//apro la sessione
session_start();

//inclusione file di connessione
include_once '../include/config.php';

//controllo se si sta uscendo dal pannello d'amministrazione
if (!empty($_SESSION['id']) && !empty($_GET['esci']) && $_GET['esci'] == 1) {
    unset($_SESSION['id']);
    header('Location: ../index.html');
}
//controllo se è settata la session e reindirizzo al pannello d'amministrazione
else if (!empty($_SESSION['id'])) {
    header('Location: admin_wines.php');
}

//dichiarazione variabili
$stampa = '';

//per stampare messaggi d'errore
if (!empty($_COOKIE['error'])) {
    $stampa .= '<div class="error_sentence">' . $_COOKIE['error'] . '</div>';
    setcookie('error', null);
}

//controllo dell'username e password
if (isset($_POST['username']) && isset($_POST['password'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {

        //controllo il formato della password
        if (preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', $_POST['password'])) {

            $username = htmlentities($_POST['username'], ENT_QUOTES);
            $password = $_POST['password'];

            $sql = 'SELECT id_user AS id FROM utenti
	        WHERE username="' . $username . '" AND password=MD5("' . $password . '")';
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                setcookie('error', 'Hai inserito le credenziali errate.');
                header('Location: login.php');
            } else {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $_SESSION['id'] = $row['id'];
                header('Location: ./admin_wines.php');
            }
        } else {
            setcookie('error', 'Hai inserito le credenziali errate.');
            header('Location: login.php');
        }

    } else {
        setcookie('error', 'Alcuni campi risultano vuoti.');
        header('Location: login.php');
    }
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/login.html');
//rimpiazzo il segnaposto e stampo in output la pagina
echo str_replace('[ERRORE]', $stampa, $pagina);

//chiudo la connessione
mysqli_close($conn);
