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
    $info_errore.="<div id='top_message'>".$_COOKIE['info']."</div>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<div id='top_message'>".$_COOKIE['error']."</div>";
    setcookie('error',null);
}

//se è settato $_POST['cancel'] significa che l'utente ha deciso di annullare l'eliminazione
if(!empty($_POST['cancel'])) header("Location: admin_users.php");

//in $_POST['users'] sono contenuti tutti gli id degli utenti che si vogliono eliminare
//se è settata anche $_GET['delete_elements'] allora procedo all'eliminazione
else if(!empty($_POST['users']) && !empty($_POST['confirm'])){
    $users = $_POST['users'];
    $num_elem = count($users);
    $sql="DELETE FROM utenti WHERE id_user = '";
    for($i=0 ; $i<$num_elem ; $i++){
        if($i!=0) $sql.="' OR id_user = '";
        $sql.=$users[$i];
    }
    $sql.="'";
    $result = mysqli_query($conn,$sql);
    //controllo la connessione
    if ($result) {
        $message = "Eliminazione avvenuta con successo. ";
        if($num_elem == 1) $message .= "Eliminato 1 utente.";
        else $message .= "Eliminati ".$num_elem." utenti.";
        setcookie('info',$message);
    }
    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");

    //ritorno in ogni caso alla gestione degli utenti
    header("Location: admin_users.php");
}
//in $_GET['users'] sono contenuti tutti gli id degli utenti che si vogliono eliminare
//se è settato solo $_GET['users'] allora mostro la richiesta di conferma per l'eliminazione
else if(!empty($_GET['users'])){
    $dati.='<form id="select_admin_buttons" action="delete_user.php" method="post">';

    $dati.='<input type="submit" class="admin_button" name="cancel" id="cancel" value="Annulla Eliminazione" />';
    $dati.='<input type="submit" class="admin_button" name="confirm" id="confirm" value="Conferma Eliminazione" /></div>';

    //controllo che nell'url abbia un array serializzato o un singolo dato
    //quindi provo a fare unserialize e se fallisce allora deduco di avere un dato unico
    //N.B.:inserire @ prima di una chiamata di funzione, evita che vengano mostrati errori che potrebbero essere lanciati da quella funzione e che potrebbero bloccare l'esecuzione del codice. === significa 'identico' mentre == significa 'uguale'
    if(($result = @unserialize($_GET['users'])) === false){
        $user = $_GET['users'];
        $sql="SELECT utenti.* FROM utenti WHERE id_user = '".$user."'";
    }
    //altrimenti sono sicuro di avere un array serializzato 
    else {
        $users = unserialize($_GET['users']); //estrapolo i dati dall'array
        $num_elem = count($users);
        $sql="SELECT utenti.* FROM utenti WHERE id_user = '";
        for($i=0 ; $i<$num_elem ; $i++){
            if($i!=0) $sql.="' OR id_user = '";
            $sql.=$users[$i];
        }
        $sql.="'";
    }

    $result=mysqli_query($conn,$sql);

    $dati.='<div class="admin_tr" id="admin_header">
                            <div id="menu_select" class="admin_td">Selezione</div>
                            <div class="admin_td">Username</div>
                            <div class="admin_td">Nome</div> 
                            <div class="admin_td">Email</div> 
                    </div>';

    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $dati.="<div class='admin_tr'>";
            $dati.="<div class ='admin_td delete_user_checkbox_column'><input id='delete_user_checkbox' type='checkbox' name='users[]' value='".$row['id_user']."' checked='checked'></div>"; 
            $dati.="<div class ='admin_td delete_user_name_column'>".$row['nome']."</div>";
            $dati.="<div class ='admin_td delete_user_username_column'>".$row['username']."</div>";
            $dati.="<div class ='admin_td delete_user_email_column'>".$row['email']."</div>";
            $dati.="</div>";
        }
    else header("Location: admin_users.php");

    $dati.="</form>";
}
//questo ramo if si verifica se si deselezionano tutti gli utenti precedentemente scelti per essere eliminati e poi si clicca su "conferma eliminazione"
else  if(!empty($_POST['confirm'])){
    setcookie('error',"Nessun utente selezionato. Eliminazione annullata.");

    //ritorno alla gestione degli utenti
    header("Location: admin_users.php");
}
//se la variabile non è settata significa che è stato manomesso l'url, allora riporto l'utente alla pagina amministrazione
else header("Location: admin_users.php");

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);
mysqli_close($conn);
?>