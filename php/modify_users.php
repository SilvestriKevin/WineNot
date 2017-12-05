<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");


$dati='';
$user='';
$info_errore='';

if(!empty($_COOKIE['info'])){
    $info_errore.="<h1>".$_COOKIE['info']."</h1>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<h1>".$_COOKIE['error']."</h1>";
    setcookie('error',null);
}

if(empty($_GET['user'])) {
    $id_user = $_POST['user'];
} else $id_user = $_GET['user'];
// prendo il valore chiave del vino che voglio andare a modificare

// controllo che non siano stati lasciati spazi vuoti e poi modifico all'interno del database i campi dati richiesti
$error='';

if(!empty($_POST['save_user'])) {

    if(!empty($_POST['nome']) && !preg_match("/^(\s)+$/",$_POST['nome']) && !empty($_POST['email']) && !empty($_POST['username']) && !preg_match("/^(\s)+$/",$_POST['username'])) {

        $username = $_POST['username'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        // siccome tutti i campi non sono vuoti allora potrò procedere con i controlli all'interno del database

        $sql = "SELECT * FROM utenti WHERE id_user='".$id_user."' AND username='".$username."' AND nome='".$nome."' AND email='".$email."'";

        $result = mysqli_query($conn,$sql);

        if(mysqli_num_rows($result)==0) { // dati diversi, quindi da cambiare


            // controllo che la mail sia del formato giusto

            if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email))
                $error.="L'email inserita non rispetta il formato corretto\n";

            if(!empty($_POST['password'])) {
                // allora cambio anche la password

                if(empty($error)) {
                    $password = $_POST['password'];

                    if(preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",$password)) { // password del formato giusto

                        $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', password=MD5('".$password."'), email='".$email."' WHERE id_user='".$id_user."'";

                        $result= mysqli_query($conn,$sql);

                        if($result) {
                            setcookie('info',"Modifica dati eseguita con successo");
                            header("Location: admin_users.php");
                        } else  $error.="Si è verificato un errore. La preghiamo di riprovare.\n";

                    } else $error.="La nuova password è in un formato sbagliato.\n";
                }

            } else { //cambio solo la i dati principali
                if(empty($error)) {
                    
                    $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', email='".$email."' WHERE id_user='".$id_user."'";

                    $result = mysqli_query($conn,$sql);

                    if($result) { // se c'è stata una modifica allora tutto ok
                        setcookie('info',"Modifica dati eseguita con successo");
                        header("Location: admin_users.php");
                    } else { // se non sono riuscito a cambiare dati nel database
                        $error.="Si è verificato un errore. La preghiamo di riprovare.\n";

                    }
                }
            }
        }

    } else  $error.="Alcuni campi dati sono stati lasciati vuoti o non sono del formato giusto.";

} 


if(!empty($error)) {
    setcookie('error',$error);
    header("Location: modify_users.php?user=".$id_user."");
}


//FORM DATI VINO

$sql = "SELECT * FROM utenti WHERE id_user='".$id_user."'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result,MYSQL_ASSOC);

$annata='';

$user.='<form action="modify_users.php" method="post">';
$user.='<input type="hidden" name="user" value="'.$id_user.'" />';

if(mysqli_num_rows($result)!=0) {
    $user.='<ul><li><label>Username: </label><input type="text" maxlength="100" name="username" id="" title="username" value="'.$row['username'].'"/></li>
                    <li><label>Nome: </label><input type="text" maxlength="100" name="nome" id="" title="nome" value="'.$row['nome'].'"/></li>
                    <li><label>Email: </label><input type="text" maxlength="100" name="email" id="" title="email" value="'.$row['email'].'"/></li>
                    <li><label>Password: </label><input type="text" maxlength="100" name="password" id="" title="password" value=""/></li>';
    $user.='<input type="submit" name="save_user" id="save_year_modifications" value="Salva" />';
    // provo a modificare i campi dati
} else $user.='<h2>Non ho trovato informazioni riguardo a questo utente.</h2>';


$user.='</form>';



//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  

$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]",$info_errore,$pagina);
echo str_replace("[DATI]", $user, $pagina);
mysqli_close($conn);
?>
