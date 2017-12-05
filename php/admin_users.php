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

if($row['admin'] == 1) { 

    if(isset($_POST['delete_selected'])){

        $users = isset($_POST['users']) ? $_POST['users'] : array();
        if (!count($users)) {
            setcookie('error',"Selezionare almeno un elemento");
            header("Location: admin_users.php");
        }   
        else{
            //per poter passare e poter usare un array tramite url posso ricorrere a due metodi:  serialize/unserialize o l'utilizzo di http_build_query che crea un url molto più lungo perchè inserisce ogni elemento singolarmente in questo modo key[indice]=valore
            header("Location: delete_user.php?users=".serialize($users));
        }
    }

    $dati.='<form action="admin_users.php" method="post">'; 

    $dati.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
    $dati.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
    $dati.='<input type="submit" name="delete_selected" id="delete_selected" value="Elimina Selezionati" /></div>';

    $dati.="<a title='Aggiungi utente' class='' href='./add_user.php' tabindex='' accesskey=''>Aggiungi Utente</a>";

    //STAMPA GLI UTENTI
    $sql = "SELECT utenti.* FROM utenti WHERE admin=0";
    $result=mysqli_query($conn,$sql);

    $dati.='<div class="admin_tr" id="admin_header">
                            <div class="admin_td">Selezione</div>
                            <div class="admin_td">Username</div>
                            <div class="admin_td">Nome</div> 
                            <div class="admin_td">Email</div>                            
                            <div class="admin_td modify_column">Modifica</div>
                            <div class="admin_td remove_column">Elimina</div>

                    </div>';

    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $dati.="<div class='admin_tr'>";  
            $dati.="<div class ='admin_td'><input type='checkbox' name='users[]' value='".$row['id_user'];
            if(isset($_POST['all_selected'])) $dati.="' checked='checked";
            $dati.="'></div>";          
            $dati.="<div class ='admin_td'>".$row['nome']."</div>";
            $dati.="<div class ='admin_td'>".$row['username']."</div>";
            $dati.="<div class ='admin_td'>".$row['email']."</div>";
            $dati.="<div class ='admin_td modify_column'><a title='Modifica utente' class='' href='./modify_users.php?user=".$row['id_user']."' tabindex='' accesskey=''>Modifica</a></div>";
            $dati.="<div class ='admin_td remove_column'><a title='Elimina utente' class='' href='./delete_user.php?users=".$row['id_user']."' tabindex='' accesskey=''>X</a></div>";
            $dati.="</div>";
        }
    else $dati.="<h2>Non sono presenti utenti.</h2>";
    $dati.="</form>";

} 
else $dati.="<h2>Non hai diritti di accesso a questa sezione.</h2>";

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);
mysqli_close($conn);
?>
