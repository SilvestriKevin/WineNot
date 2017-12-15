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
$error = '';

//stampo i messaggi informativi e/o di errore
if(!empty($_COOKIE['info'])){
    $info_errore.="<div>".$_COOKIE['info']."</div>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<div>".$_COOKIE['error']."</div>";
    setcookie('error',null);
}

if(!empty($_POST['nome']) && !preg_match("/^(\s)+$/",$_POST['nome']) && !empty($_POST['email']) && !empty($_POST['username']) && !preg_match("/^(\s)+$/",$_POST['username'])){

    $username = $_POST['username'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    //controllo che i dati presenti non siano uguali a quelli già presenti nel database

    $sql = "SELECT * FROM utenti WHERE id_user='".$_SESSION['id']."' AND username='".$username."' AND nome='".$nome."' AND email='".$email."'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==0) { // i dati inseriti dall'utente non sono uguali a quelli già presenti nel database, quindi l'utente vuole cambiare almeno un campo dato

        // controllo che la mail sia del formato giusto

        if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email)) {
            $error.="L'email inserita non rispetta il formato corretto\n";
        }  

        // controllo SE anche i campi password sono settati e non vuoti

        if(!empty($_POST['actual_password']) && !empty($_POST['new_password'])) {

            $current_password = $_POST['actual_password'];
            $new_password = $_POST['new_password'];

            // controllo se la 'actual_password' coincide con la password del database

            $sql = "SELECT * FROM utenti WHERE id_user='".$_SESSION['id']."' AND password =MD5('".$current_password."')";

            $result = mysqli_query($conn,$sql);


            if(mysqli_num_rows($result)!=0) { // vuol dire che la password che l'utente ha inserito all'interno della casella 'Password Corrente" è giusta

                //controllo che la password corrente e quella nuova non coincidano

                if($new_password != $current_password) {
                    // se sono diverse allora dovrò salvare la prima nel database 
                    // se è del formato giusto
                    if(empty($error)){
                        if(preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",$_POST['new_password'])) {

                            // posso salvare anche la nuova password nel database

                            $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', password=MD5('".$new_password."'), email='".$email."' WHERE id_user='".$_SESSION['id']."'";

                            $result = mysqli_query($conn,$sql);

                            if($result) { // se c'è stata una modifica allora tutto ok
                                setcookie('info',"Modifica dati eseguita con successo");
                                header("Location: admin_profile.php");
                            } else { // se non sono riuscito a cambiare dati nel database
                                $error.="Si è verificato un errore. La preghiamo di riprovare.\n";

                            }

                        } else $error.="La nuova password è in un formato sbagliato.\n";
                    } 

                } else { // le password erano uguali quindi cambio solo i dati esclusi la password

                    if(empty($error)) {
                        $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', email='".$email."' WHERE id_user='".$_SESSION['id']."'";

                        $result = mysqli_query($conn,$sql);

                        if($result) { // se c'è stata una modifica allora tutto ok
                            setcookie('info',"Modifica dati eseguita con successo");
                            header("Location: admin_profile.php");
                        } else { // se non sono riuscito a cambiare dati nel database
                            $error.="Si è verificato un errore. La preghiamo di riprovare.\n";

                        }
                    }
                }

            } else $error.="La password inserita non &egrave; corretta.\n";     


        } else { // salvo SOLO i dati relativi a username, nome ed email

            if(!empty($_POST['new_password']) || !empty($_POST['actual_password']))
                $error.="Entrambi i campi password devono essere compilati.\n";

            if(empty($error)){
                $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', email='".$email."' WHERE id_user='".$_SESSION['id']."'";

                $result = mysqli_query($conn,$sql);

                if($result) { // se c'è stata una modifica allora tutto ok
                    setcookie('info',"Modifica dati eseguita con successo");
                    header("Location: admin_profile.php");
                } else { // se non sono riuscito a cambiare dati nel database
                    $error.="Si è verificato un errore. La preghiamo di riprovare.\n";

                }

            }
        }





    }



} else { // faccio capire all'utente che ha lasciato dei campi vuoti(esclusi password)
    // oppure ci sono degli spazi all'interno di username e nome

    if(!empty($_POST['save_profile']))
        $error.="Alcuni campi dati sono stati lasciati vuoti oppure i campi dato Username(o Nome) contengono degli spazi vietati.\n";

}

if(!empty($error)){
    setcookie('error',$error);
    header("Location: admin_profile.php");
}

//FORM DATI PROFILO

$sql = "SELECT utenti.* FROM utenti WHERE id_user='".$_SESSION['id']."'";
$result=mysqli_query($conn,$sql);

$row = mysqli_fetch_array($result,MYSQL_ASSOC);

$dati.='<h1 id="admin_title">Dati del profilo</h1>';

$dati.='<form id="admin_profile_page" action="admin_profile.php" method="post">';


if(mysqli_num_rows($result)!=0){
    $dati.='<ul>
                    <li><label>Username</label></li><li><input type="text" maxlength="100" name="username" id="" title="username" value="'.$row['username'].'"/></li>
                    <li><label>Nome</label></li><li><input type="text" maxlength="100" name="nome" id="" title="nome" value="'.$row['nome'].'"/></li>
                    <li><label>Email</label></li><li><input type="text" maxlength="100" name="email" id="" title="email" value="'.$row['email'].'"/></li>
                    <li><label>Password attuale</label></li><li><input type="text" maxlength="100" name="actual_password" id="" title="password attuale" value=""/></li>
                    <li><label>Password nuova</label></li><li><input type="text" maxlength="100" name="new_password" id="" title="password nuova" value=""/></li>
                </ul>';
    $dati.='<input type="submit" class="search_button" name="save_profile" id="save_admin_profile" value="Salva" />';
} else 
    $dati.='<h2>Ci sono dei problemi con il database.</h2>';

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
