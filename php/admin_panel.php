<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

$vini='';
$info_errore='';

if(!empty($_COOKIE['info'])){
    $info_errore.="<li>".$_COOKIE['info']."</li>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<li>".$_COOKIE['error']."</li>";
    setcookie('error',null);
}

if(isset($_GET['section'])) $section = $_GET['section'];
else if(isset($_POST['section'])) $section = $_POST['section'];

if(isset($section)){
    switch($section){
        case 'garbage':
            if(isset($_POST['delete_finally_selected'])){

                $wines = isset($_POST['wines']) ? $_POST['wines'] : array();
                if (!count($wines)) {
                    setcookie('error',"Selezionare almeno un elemento");
                    header("Location: admin_panel.php?section=garbage");
                }   
                else{
                    $num_elem = count($wines);
                    $sql="DELETE vini WHERE id_wine ='";
                    for($i=0 ; $i<$num_elem ; $i++){
                        if($i!=0) $sql.="' or '";
                        $sql.=$wines[$i];
                    }
                    $sql.="'";
                    $result = mysqli_query($conn,$sql);
                    //controllo la connessione
                    if ($result) {
                        setcookie('info',"Elementi eliminati definitivamente");
                        header("Location: admin_panel.php?section=garbage");
                    }
                    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
                    header("Location: admin_panel.php?section=garbage");
                }
            }

            if(isset($_POST['restore_selected'])){

                $wines = isset($_POST['wines']) ? $_POST['wines'] : array();
                if (!count($wines)) {
                    setcookie('error',"Selezionare almeno un elemento");
                    header("Location: admin_panel.php?section=garbage");
                }   
                else{
                    $num_elem = count($wines);
                    $sql="UPDATE vini SET cestino = 0 WHERE id_wine ='";
                    for($i=0 ; $i<$num_elem ; $i++){
                        if($i!=0) $sql.="' or id_wine = '";
                        $sql.=$wines[$i];
                    }
                    $sql.="'";
                    $result = mysqli_query($conn,$sql);
                    //controllo la connessione
                    if ($result) {
                        setcookie('info',"Elementi ripristinati");
                        header("Location: admin_panel.php?section=garbage");
                    }
                    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
                    header("Location: admin_panel.php?section=garbage");
                }
            }

            $vini.='<input type="hidden" name="section" value="garbage" />';

            $vini.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
            $vini.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
            $vini.='<input type="submit" name="restore_selected" id="restore_selected" value="Ripristina Selezionati" />';
            $vini.='<input type="submit" name="delete_finally_selected" id="delete_finally_selected" value="Elimina Selezionati" /></div>';

            //STAMPA I VINI (PRESENTI NEL CESTINO)
            $sql = "SELECT vini.* FROM vini WHERE cestino=1";
            $result=mysqli_query($conn,$sql);

            $vini.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Denominazione</div>
                            <div class="wines_td">Tipologia</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td modify_column">Elimina</div>

                    </div>';

            if(mysqli_num_rows($result)!=0)
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $vini.="<div class='wines_tr'>";
                    $vini.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['id_wine'];
                    if(isset($_POST['all_selected'])) $vini.="' checked='checked";
                    $vini.="'></div>";
                    $vini.="<div class ='wines_td'>".$row['denominazione']."</div>";
                    $vini.="<div class ='wines_td'>".$row['tipologia']."</div>";
                    $vini.="<div class ='wines_td'>".$row['annata']."</div>";
                    $vini.="<div class ='wines_td remove_column'><a title='Elimina definitivamente vino' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></div>";
                    $vini.="</div>";
                }
            else $vini.="<h2>Non sono presenti vini.</h2>";           
            break;


        case 'years':

            if(isset($_POST['delete_finally_selected'])){

                $years = isset($_POST['years']) ? $_POST['years'] : array();
                if (!count($years)) {
                    setcookie('error',"Selezionare almeno un elemento");
                    header("Location: admin_panel.php?section=years");
                }   
                else{
                    $num_elem = count($years);
                    $sql="DELETE annate WHERE anno ='";
                    for($i=0 ; $i<$num_elem ; $i++){
                        if($i!=0) $sql.="' or '";
                        $sql.=$years[$i];
                    }
                    $sql.="'";
                    $result = mysqli_query($conn,$sql);
                    //controllo la connessione
                    if ($result) {
                        setcookie('info',"Elementi eliminati definitivamente");
                        header("Location: admin_panel.php?section=years");
                    }
                    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
                    header("Location: admin_panel.php?section=years");
                }
            }

            if(isset($_POST['restore_selected'])){

                $years = isset($_POST['years']) ? $_POST['years'] : array();
                if (!count($years)) {
                    setcookie('error',"Selezionare almeno un elemento");
                    header("Location: admin_panel.php?section=years");
                }   
                else{
                    $num_elem = count($wines);
                    $sql="UPDATE annate SET cestino = 0 WHERE anno ='";
                    for($i=0 ; $i<$num_elem ; $i++){
                        if($i!=0) $sql.="' or anno = '";
                        $sql.=$years[$i];
                    }
                    $sql.="'";
                    $result = mysqli_query($conn,$sql);
                    //controllo la connessione
                    if ($result) {
                        setcookie('info',"Elementi ripristinati");
                        header("Location: admin_panel.php?section=years");
                    }
                    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
                    header("Location: admin_panel.php?section=years");
                }
            }

            $vini.='<input type="hidden" name="section" value="years" />';

            $vini.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
            $vini.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
            $vini.='<input type="submit" name="restore_selected" id="restore_selected" value="Ripristina Selezionati" />';
            $vini.='<input type="submit" name="delete_finally_selected" id="delete_finally_selected" value="Elimina Selezionati" /></div>';

            //STAMPA I VINI (PRESENTI NELLE ANNATE MIGLIORI)
            $sql = "SELECT annate.* FROM annate WHERE migliore=1";
            $result=mysqli_query($conn,$sql);

            $vini.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td">Qualit&agrave;</div>
                            <div class="wines_td modify_column">Modifica</div>
                            <div class="wines_td remove_column">Cestina</div>

                    </div>';

            if(mysqli_num_rows($result)!=0)
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $vini.="<div class='wines_tr'>";
                    $vini.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['anno'];
                    if(isset($_POST['all_selected'])) $vini.="' checked='checked";
                    $vini.="'></div>";
                    $vini.="<div class ='wines_td'>".$row['anno']."</div>";
                    $vini.="<div class ='wines_td'>".$row['qualita']."</div>";
                    $vini.="<div class ='wines_td modify_column'><a title='Modifica vino' class='' href='./modify_wine.php' tabindex='' accesskey=''>Modifica</a></div>";
                    $vini.="<div class ='wines_td remove_column'><a title='Elimina annata' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></div>";
                    $vini.="</div>";
                }
            else $vini.="<h2>Non sono presenti annate.</h2>";

            break;

        case 'profile':

            $sql = "SELECT utenti.* FROM utenti WHERE id_user='".$_SESSION['id']."'";
            $result=mysqli_query($conn,$sql);

            $row = mysqli_fetch_array($result,MYSQL_ASSOC);

            // faccio lo stesso il controllo perchè non si sa mai?!
            if(mysqli_num_rows($result)!=0){
                $vini.='<ul>
                    <li><label>Username: </label><input type="text" maxlength="100" name="username" id="" title="username" value="'.$row['username'].'"/></li>
                    <li><label>Nome: </label><input type="text" maxlength="100" name="nome" id="" title="nome" value="'.$row['nome'].'"/></li>
                    <li><label>Email: </label><input type="text" maxlength="100" name="email" id="" title="email" value="'.$row['email'].'"/></li>
                    <li><label>Password attuale: </label><input type="text" maxlength="100" name="actual_password" id="" title="password attuale" value=""/></li>
                    <li><label>Password nuova: </label><input type="text" maxlength="100" name="new_password" id="" title="password nuova" value=""/></li>
                </ul>';
                $vini.='<input type="submit" name="save_profile" id="save_profile_modifications" value="Salva" />';
            } else 
                $vini.='<h2>Ci sono dei problemi con il database.</h2>';





            if(isset($_POST['actual_password']) && $_POST['actual_password']) {

                if(preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",$_POST['actual_password']) && preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",$_POST['new_password'])) {

                    // dichiaro le variabili necessarie
                    $current_password = $_POST['actual_password'];
                    $new_password = $_POST['new_password'];

                    $sql = "SELECT * FROM utenti WHERE id_user='".$_SESSION['id']."' AND password =MD5('".$current_password."')";

                    $result = mysql_query($conn,$sql);

                    // controllo la connessione
                    if(mysqli_num_rows($result)!=0) {
                        if($new_password !=$current_password) {
                            // modifico la password nel database

                            $sql = "UPDATE utenti SET password=MD5('".$new_password."') WHERE id_user='".$_SESSION['id']."'";

                            $result = mysqli_query($conn,$sql);
                            //controllo la connessione
                            if($result) {
                                setcookie('info',"Modifica password eseguita con successo.");
                                header("Location: admin_panel.php?section=profile");
                            } else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare.");
                        } 
                    } else setcookie('error',"La password inserita non &egrave; corretta.");

                } else {
                    setcookie('error',"La password inserita non rispetta il formato corretto");
                    header("Location: admin_panel.php?section=profile");
                }
            } else if(!empty($_POST['nome']) && !preg_match("/^(\s)+$/",$_POST['nome']) && !empty($_POST['email']) && !empty($_POST['username'])) { // controllo che il campo delle password rispetti le policy

                // controllo campi vuoti o spazi

                $nome = $_POST['nome'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];

                // controllo formato email

                if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email)) {
                    setcookie('error',"L'email inserita non rispetta il formato corretto");
                    header("Location: admin_panel.php?section=profile");
                } else {
                    // controllo che la password sia uguale a quella inserita nel database

                    $sql = "SELECT * FROM utenti WHERE id_user='".$_SESSION['id']."' AND password=MD5('".$password."')";

                    $result = mysqli_query($conn,$sql);

                    if(mysqli_num_rows($result) == 0) {
                        setcookie('error',"La password inserita non &egrave; corretta");
                        header("Location: admin.php?section=profile");
                    } else {
                        // posso modificare i dati all'interno del database


                        $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', password='".$new_password."', email='".$email."' WHERE id_user='".$_SESSION['id']."'";

                        $result = mysqli_query($conn,$sql);


                    }

                } 


//            } else {    // è stato lasciato un campo vuoto
//
//                setcookie('error',"Uno o più campi sono stati lasciati vuoti.");
//                header("Location: admin_panel.php?section=profile");
//
//            } 
           

    break;


    case 'users':
    $sql = "SELECT admin FROM utenti WHERE id_user='".$_SESSION['id']."'";
    $result=mysqli_query($conn,$sql);

    $row = mysqli_fetch_array($result,MYSQL_ASSOC);

    if($row['admin'] == 1) { 
        //STAMPA I VINI (PRESENTI NELLE ANNATE MIGLIORI)
        $sql = "SELECT utenti.* FROM utenti WHERE admin=0";
        $result=mysqli_query($conn,$sql);

        $vini.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Username</div>
                            <div class="wines_td">Password</div>
                            <div class="wines_td">Nome</div> 
                            <div class="wines_td">Email</div>
                            <div class="wines_td remove_column">Elimina</div>

                    </div>';

        if(mysqli_num_rows($result)!=0)
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $vini.="<div class='wines_tr'>";
                $vini.="<div class ='wines_td'>".$row['username']."</div>";
                $vini.="<div class ='wines_td'>".$row['password']."</div>";
                $vini.="<div class ='wines_td'>".$row['nome']."</div>";
                $vini.="<div class ='wines_td'>".$row['email']."</div>";
                $vini.="<div class ='wines_td remove_column'><a title='Elimina utente' class='' href='./delete_user.php' tabindex='' accesskey=''>X</a></div>";
                $vini.="</div>";
                $vini.="<div class ='wines_td'><a title='Elimina annata' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></div>";
                $vini.="</div>";
            }
        else $vini.="<h2>Non sono presenti utenti.</h2>";
    } else {
        $vini.="<h2>Non hai diritti di accesso a questa sezione.</h2>";
    }

    $vini.="<a title='Aggiungi utente' class='' href='./add_user.php' tabindex='' accesskey=''>Aggiungi Utente</a>";
    break;
}
}
else {
    if(isset($_POST['delete_selected'])){

        $wines = isset($_POST['wines']) ? $_POST['wines'] : array();
        if (!count($wines)) {
            setcookie('error',"Selezionare almeno un elemento");
            header("Location: admin_panel.php");
        }   
        else{
            $num_elem = count($wines);
            $sql="UPDATE vini SET cestino = 1 WHERE id_wine = '";
            for($i=0 ; $i<$num_elem ; $i++){
                if($i!=0) $sql.="' OR id_wine = '";
                $sql.=$wines[$i];
            }
            $sql.="'";
            $result = mysqli_query($conn,$sql);
            //controllo la connessione
            if ($result) {
                setcookie('info',"Elementi spostati nel cestino");
                header("Location: admin_panel.php");
            }
            else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
            header("Location: admin_panel.php");
        }
    }

    $vini.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
    $vini.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
    $vini.='<input type="submit" name="delete_selected" id="delete_selected" value="Cestina Selezionati" /></div>';

    //STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
    $sql = "SELECT vini.* FROM vini WHERE cestino=0";
    $result=mysqli_query($conn,$sql);

    $vini.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Denominazione</div>
                            <div class="wines_td">Tipologia</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td modify_column">Modifica</div>
                            <div class="wines_td remove_column">Cestina</div>
                    </div>';

    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vini.="<div class='wines_tr'>";
            $vini.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['id_wine'];
            if(isset($_POST['all_selected'])) $vini.="' checked='checked";
            $vini.="'></div>";
            $vini.="<div class ='wines_td'>".$row['denominazione']."</div>";
            $vini.="<div class ='wines_td'>".$row['tipologia']."</div>";
            $vini.="<div class ='wines_td'>".$row['annata']."</div>";
            $vini.="<div class ='wines_td modify_column'><a title='Modifica vino' class='' href='./modify_wine.php' tabindex='' accesskey=''>Modifica</a></div>";
            $vini.="<div class ='wines_td remove_column'><a title='Elimina vino' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></div>";
            $vini.="</div>";
        }
    else $vini.="<h2>Non sono presenti vini.</h2>";
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[VINI]", $vini, $pagina);
mysqli_close($conn);
?>
