<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

$dati='';
$info_errore='';

//stampo i messaggi informativi e/o di errore
if(!empty($_COOKIE['info'])){
    $info_errore.="<li>".$_COOKIE['info']."</li>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<li>".$_COOKIE['error']."</li>";
    setcookie('error',null);
}


$sql = "SELECT admin FROM utenti WHERE id_user='".$_SESSION['id']."'";
$result=mysqli_query($conn,$sql);

$row = mysqli_fetch_array($result,MYSQL_ASSOC);

$dati.='<form action="admin_users.php" method="post">';  

if($row['admin'] == 1) { 
    //STAMPA GLI UTENTI
    $sql = "SELECT utenti.* FROM utenti WHERE admin=0";
    $result=mysqli_query($conn,$sql);

    $dati.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Username</div>
                            <div class="wines_td">Nome</div> 
                            <div class="wines_td">Email</div>
                            <div class="wines_td remove_column">Elimina</div>

                    </div>';

    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $dati.="<div class='wines_tr'>";
            $dati.="<div class ='wines_td'>".$row['username']."</div>";
            $dati.="<div class ='wines_td'>".$row['nome']."</div>";
            $dati.="<div class ='wines_td'>".$row['email']."</div>";
            $dati.="<div class ='wines_td remove_column'><a title='Elimina utente' class='' href='./delete_user.php' tabindex='' accesskey=''>X</a></div>";
            $dati.="</div>";
            $dati.="</div>";
        }
    else $dati.="<h2>Non sono presenti utenti.</h2>";
} else {
    $dati.="<h2>Non hai diritti di accesso a questa sezione.</h2>";
}

$dati.="<a title='Aggiungi utente' class='' href='./add_user.php' tabindex='' accesskey=''>Aggiungi Utente</a>";

$dati.="</form>";

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);
mysqli_close($conn);
?>
