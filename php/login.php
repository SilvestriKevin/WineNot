<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//per uscire dal pannello d'amministrazione
if(!empty($_SESSION["id"]) && !empty($_GET["esci"]) && $_GET["esci"]==1){
    unset($_SESSION["id"]);
    header("Location: ../index.html");
}
else if(!empty($_SESSION["id"])) header("Location: admin_wines.php");


$stampa="";

//per stampare messaggi d'errore
if(!empty($_COOKIE["error"])){
    $stampa.='<h2 id="gonna_delete_user">'.$_COOKIE["error"].'</h2>';
    setcookie("error",null);
}

//controllo dell'username e password
if(isset($_POST["username"]) && isset($_POST["password"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    if(!empty($username) && !empty($password)){

        $sql = "SELECT id_user AS id FROM utenti
	WHERE username='".htmlentities($username, ENT_QUOTES)."' AND password=MD5('".htmlentities($password, ENT_QUOTES)."')";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 0){
            setcookie("error","Hai inserito le credenziali errate".$sql);
            header("Location: login.php");
        }
        else{
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $_SESSION["id"] = $row["id"];
            header("Location: ./admin_wines.php");
        }
    }
    else{
        setcookie("error","Alcuni campi risultano vuoti");
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
