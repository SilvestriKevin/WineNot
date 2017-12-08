<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

$user='';
$info_errore='';

if(!empty($_COOKIE['info'])){
    $info_errore.="<li>".$_COOKIE['info']."</li>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<li id='error_admin_message'>".$_COOKIE['error']."</li>";
    setcookie('error',null);
}

 $sql = "SELECT admin FROM utenti WHERE id_user='".$_SESSION['id']."'";
            $result=mysqli_query($conn,$sql);

            $row = mysqli_fetch_array($result,MYSQL_ASSOC);

            $user.='<h1 id="admin_title">Inserisci un nuovo utente</h1>';

            $user.='<form id="admin_profile_page" action="add_user.php" method="post">';        

            if($row['admin'] == 1) { 

                $user.= '<fieldset>
                    <ul>
                    <li id="important_message"><span>Tutti i campi sono obbligatori</span></li>
                    <li><label>Nome:</label>
                    <input type="text" maxlength="50" name="nome" id="nome" title="nome" tabindex="1"/>
                    <li><label>Username:</label>
                    <input type="text" maxlength="50" name="username" id="username" title="username" tabindex="1"/>
                        </li><li><label>Indirizzo email:</label>
                    <input type="text" maxlength="50" name="email" id="email" title="email" value="example@gmail.com" onfocus="placeHolder(this);" tabindex="5"/>
                        </li><li><label>Password:</label>
                    <input type="password" maxlength="100" name="password" id="password_user" title="password" tabindex="6"/>
                        </li><li><label>Conferma Password:</label>
                    <input type="password" maxlength="100" name="conferma_password" id="conferma_password" title="conferma_password" tabindex="7"/></li>
                    </ul>
                        <input type="submit" class="search_button" name="register" value="Salva" id="save_admin_profile" accesskey="s" tabindex="8"/>

                </fieldset>';


            } else $user.="<h2>Non hai accesso a questa pagina.</h2>";

            if(isset($_POST['nome']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['conferma_password'])) {

                //controllo che non sia stati lasciati campi vuoti o siano spazi scritti solo spazi
                if (!empty($_POST['nome']) && !preg_match("/^(\s)+$/",$_POST['nome']) && !empty($_POST['username']) && !preg_match("/^(\s)+$/",$_POST['username']) && !empty($_POST['email']) && !preg_match("/^(\s)+$/",$_POST['email']) && !empty($_POST['password']) && !preg_match("/^(\s)+$/",$_POST['password']) && !empty($_POST['conferma_password']) && !preg_match("/^(\s)+$/",$_POST['conferma_password']))
                {
                    // dichiaro le variabili
                    $nome = $_POST['nome'];
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $conferma_password = $_POST['conferma_password'];

                    $message = '';

                    if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i",$email)) {
                        $message="Formato email errato. ";
                    }
                    if(!preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",$password)) {
                        $message.="Formato password errato. ";
                    }
                    if($password!=$conferma_password) {
                        $message.="Le password non corrispondono. ";
                    }

                    if(empty($message)) { // se non ci sono stati problemi

                        //controllo che la email inserita non sia già presente nel database 
                        $sql = "SELECT email FROM utenti WHERE email='".$email."' ";
                        $result = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($result) != 0){
                            setcookie('error',"L'email inserita esiste gi&agrave;");
                        } else {
                            // inserisco i dati nel database

                            $sql = "INSERT INTO utenti (nome, username,  password,email) VALUES ('".$nome."','".$username."', MD5('".$password."'),'".strtolower($email)."')";
                            //controllo la connessione
                            if (mysqli_query($conn, $sql) == TRUE) {
                                setcookie('info',"Aggiunta avvenuta con successo.");
                                header("Location: admin_users.php");
                            } else {
                                setcookie('error',"Si &egrave; verificato un errore. La preghiamo di riprovare");
                            }

                        }

                    } else {
                        setcookie('error',$message);
                        header("Location: add_user.php");
                    }

                } else {
                 setcookie('error','Alcuni campi risultano vuoti');
        header("Location: add_user.php"); 
            } 
            }  


            $user.="</form>";

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina 

$pagina = str_replace("[SEARCH_WINE]", '', $pagina);

$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $user, $pagina);
mysqli_close($conn);
?>
