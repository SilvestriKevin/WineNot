<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(isset($_SESSION['id'])) header("Location: admin_panel.php");

$stampa='';

if(!empty($_COOKIE['error'])){
    $stampa.="<h2 id='gonna_delete_user'>".$_COOKIE['error']."</h2>";
    setcookie('error',null);
}

if(isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if(!empty($username) && !empty($password)){

        $sql = "SELECT id_user AS id FROM utenti
	WHERE username='".escapingText($username)."'  and password=MD5('".escapingText($password)."')";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 0){
            setcookie('error','Hai inserito le credenziali errate');
            header("Location: login.php");
        }
        else{
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $_SESSION['id'] = $row['id'];
            header("Location: ./admin_panel.php");
        }
    }
    else{
        setcookie('error','Alcuni campi risultano vuoti');
        header("Location: login.php");
    }
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/login.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[ERRORE]", $stampa, $pagina);
mysqli_close($conn);
?>
