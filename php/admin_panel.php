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
$annata='';
$tipologia='';
$ordine='';
$improved_search='';

//stampo i messaggi informativi e/o di errore
if(!empty($_COOKIE['info'])){
    $info_errore.="<li>".$_COOKIE['info']."</li>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<li>".$_COOKIE['error']."</li>";
    setcookie('error',null);
}

//catturo il valore della variabile section nell'url e se non è presente controllo anche la variabile post nel caso in cui la pagina chiamante sia sempre admin_panel.php
if(isset($_GET['section'])) $section = $_GET['section'];
else if(isset($_POST['section'])) $section = $_POST['section'];

//se è settata la section
if(isset($section)){
    //in base alla section cambia quello che vedo. Le sezioni sono years, users e profile. La quarta sezione è quella che vedo quando non è setta la variabile section cioè la gestione dei vini che è la sezione che vede appena apro la pagina.
    switch($section){
            //gestione annate
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

            $dati.='<form action="admin_panel.php" method="post">';

            $dati.='<input type="hidden" name="section" value="years" />';

            $dati.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
            $dati.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
            $dati.='<input type="submit" name="restore_selected" id="restore_selected" value="Ripristina Selezionati" />';
            $dati.='<input type="submit" name="delete_finally_selected" id="delete_finally_selected" value="Elimina Selezionati" /></div>';

            //STAMPA I VINI (PRESENTI NELLE ANNATE MIGLIORI)
            $sql = "SELECT annate.* FROM annate WHERE migliore=1";
            $result=mysqli_query($conn,$sql);

            $dati.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td">Qualit&agrave;</div>
                            <div class="wines_td modify_column">Modifica</div>
                            <div class="wines_td remove_column">Cestina</div>

                    </div>';

            if(mysqli_num_rows($result)!=0)
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $dati.="<div class='wines_tr'>";
                    $dati.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['anno'];
                    if(isset($_POST['all_selected'])) $dati.="' checked='checked";
                    $dati.="'></div>";
                    $dati.="<div class ='wines_td'>".$row['anno']."</div>";
                    $dati.="<div class ='wines_td'>".$row['qualita']."</div>";
                    $dati.="<div class ='wines_td modify_column'><a title='Modifica vino' class='' href='./modify_year.php?year=".$row['anno']."' tabindex='' accesskey=''>Modifica</a></div>";
                    $dati.="<div class ='wines_td remove_column'><a title='Elimina annata' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></div>";
                    $dati.="</div>";
                }
            else {
                $dati.="<h2>Non sono presenti annate.</h2>";
            }

            $dati.="<a title='Aggiungi Annata' class='' href='./add_year.php' tabindex='' accesskey=''>Aggiungi Annata</a>";
            $dati.="</form>";

            break;

        case 'users':
            $sql = "SELECT admin FROM utenti WHERE id_user='".$_SESSION['id']."'";
            $result=mysqli_query($conn,$sql);

            $row = mysqli_fetch_array($result,MYSQL_ASSOC);

            $dati.='<form action="admin_panel.php" method="post">';  

            $dati.='<input type="hidden" name="section" value="users" />';

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
            break;

        case 'profile':
            // se tutti i campi tranne password attuale/password nuova NON sono vuoti
            // allora posso aggiornare quei campi dato E (potenzialmente) anche la password

            /**/ 
            $error = '';

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
                    } else { // se è del formato giusto, posso andare avanti



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

                                    if(preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",$_POST['new_password'])) {

                                        // posso salvare anche la nuova password nel database

                                        $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', password='".$new_password."', email='".$email."' WHERE id_user='".$_SESSION['id']."'";

                                        $result = mysqli_query($conn,$sql);

                                        if($result) { // se c'è stata una modifica allora tutto ok
                                            setcookie('info',"Modifica dati eseguita con successo");
                                            header("Location: admin_panel.php?section=profile");
                                        } else { // se non sono riuscito a cambiare dati nel database
                                            $error.="Si è verificato un errore. La preghiamo di riprovare.\n";

                                        }

                                    } else { // password formato sbagliato
                                        $error.="La nuova password è in un formato sbagliato.\n";
                                    }
                                } else { // le password erano uguali quindi cambio solo i dati esclusi la password
                                    $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', email='".$email."' WHERE id_user='".$_SESSION['id']."'";

                                    $result = mysqli_query($conn,$sql);

                                    if($result) { // se c'è stata una modifica allora tutto ok
                                        setcookie('info',"Modifica dati eseguita con successo");
                                        header("Location: admin_panel.php?section=profile");
                                    } else { // se non sono riuscito a cambiare dati nel database
                                        $error.="Si è verificato un errore. La preghiamo di riprovare.\n";

                                    }
                                }

                            } else $error.="La password inserita non &egrave; corretta.\n";     


                        } else { // salvo SOLO i dati relativi a username, nome ed email


                            $sql = "UPDATE utenti SET nome='".$nome."', username='".$username."', email='".$email."' WHERE id_user='".$_SESSION['id']."'";

                            $result = mysqli_query($conn,$sql);

                            if($result) { // se c'è stata una modifica allora tutto ok
                                setcookie('info',"Modifica dati eseguita con successo");
                                header("Location: admin_panel.php?section=profile");
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
                header("Location: admin_panel.php?section=profile");
            }

            //FORM DATI PROFILO

            $sql = "SELECT utenti.* FROM utenti WHERE id_user='".$_SESSION['id']."'";
            $result=mysqli_query($conn,$sql);

            $row = mysqli_fetch_array($result,MYSQL_ASSOC);

            $dati.='<form action="admin_panel.php" method="post">';

            $dati.='<input type="hidden" name="section" value="profile" />';

            if(mysqli_num_rows($result)!=0){
                $dati.='<ul>
                    <li><label>Username: </label><input type="text" maxlength="100" name="username" id="" title="username" value="'.$row['username'].'"/></li>
                    <li><label>Nome: </label><input type="text" maxlength="100" name="nome" id="" title="nome" value="'.$row['nome'].'"/></li>
                    <li><label>Email: </label><input type="text" maxlength="100" name="email" id="" title="email" value="'.$row['email'].'"/></li>
                    <li><label>Password attuale: </label><input type="text" maxlength="100" name="actual_password" id="" title="password attuale" value=""/></li>
                    <li><label>Password nuova: </label><input type="text" maxlength="100" name="new_password" id="" title="password nuova" value=""/></li>
                </ul>';
                $dati.='<input type="submit" name="save_profile" id="save_profile_modifications" value="Salva" />';
            } else 
                $dati.='<h2>Ci sono dei problemi con il database.</h2>';

            $dati.="</form>";
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
            //per poter passare e poter usare un array tramite url posso ricorrere a due metodi:  serialize/unserialize o l'utilizzo di http_build_query che crea un url molto più lungo perchè inserisce ogni elemento singolarmente in questo modo key[indice]=valore
            header("Location: delete_wine.php?wines=".serialize($wines));
        }
    }


    //SELECT ANNATA NEL FORM
    $sql = "SELECT annata FROM vini GROUP BY annata ORDER BY annata";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $annata.="<option value='".$row['annata']."'";
            if(!empty($_GET['annata']) && $_GET['annata']==$row['annata']) $annata.=" selected='selected'";
            $annata.=">".$row['annata']."</option>";
        }

    //SELECT TIPOLOGIA NEL FORM
    $array_tipologie=array('bianco','rosso','nero','ros&egrave');
    $num_elementi=count($array_tipologie);
    for($i=0 ; $i<$num_elementi ; $i++){
        $tipologia.="<option value='".$array_tipologie[$i]."'";
        if(!empty($_GET['tipologia']) && entityAccentedVowels($_GET['tipologia'])==$array_tipologie[$i]) $tipologia.=" selected='selected'";
        $tipologia.=">".$array_tipologie[$i]."</option>";
    }

    //SELECT ORDINE NEL FORM
    $array_ordine=array('nome','annata','tipologia','gradazione','formato');
    $num_elementi=count($array_ordine);
    for($i=0 ; $i<$num_elementi ; $i++){
        $ordine.="<option value='".$array_ordine[$i]."'";
        if(!empty($_GET['ordine']) && $_GET['ordine']==$array_ordine[$i]) $ordine.=" selected='selected'";
        $ordine.=">".$array_ordine[$i]."</option>";
    }

    $text_search = 'vini';


    //STAMPA I VINI SECONDO I PARAMETRI DI RICERCA
    if(!empty($_GET['annata']) && !empty($_GET['tipologia']) && !empty($_GET['ordine'])){

        if(!empty($_GET['search'])){
            //chiamo la funzione in lib.php che controlla il testo inserito. (controllare ricerca su homie)

            // rendo tutto in minuscolo
            $search = strtolower($_GET['search']);

            // pulisco la stringa
            $search = cleanInput($search);

            $counter=0;
            while(!empty($search[$counter])) {

                if($counter>0) {
                    $text_search = "( SELECT vini.* FROM ".$text_search." WHERE ( vini.nome LIKE '%".$search[$counter]."%' OR vini.denominazione LIKE '%".$search[$counter]."%' OR vini.tipologia LIKE '%".$search[$counter]."%' OR vini.vitigno LIKE '%".$search[$counter]."%' OR vini.gradazione LIKE '%".$search[$counter]."%' ) ) AS vini";
                }
                else{
                    $text_search = "( SELECT vini.* FROM vini WHERE ( vini.nome LIKE '%".$search[$counter]."%' OR vini.denominazione LIKE '%".$search[$counter]."%' OR vini.tipologia LIKE '%".$search[$counter]."%' OR vini.vitigno LIKE '%".$search[$counter]."%' OR vini.gradazione LIKE '%".$search[$counter]."%' ) ) AS vini";
                }

                $counter++;

            }
        }



        if($_GET['annata']!='All') {
            $improved_search.=" WHERE annata='".$_GET['annata']."'";
        }

        if($_GET['tipologia']!='All'){
            if(!empty($improved_search)) $improved_search.=" AND tipologia='".entityAccentedVowels($_GET['tipologia'])."'";
            else {
                $improved_search.=" WHERE tipologia='".entityAccentedVowels($_GET['tipologia'])."'";
            }
        }


        //STAMPA I VINI 
        $sql = "SELECT vini.* FROM ".$text_search.$improved_search." ORDER BY ".$_GET['ordine'];

    }
    //STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
    else $sql = "SELECT vini.* FROM vini";


    $result=mysqli_query($conn,$sql);


    $dati.='<form action="admin_panel.php" method="post">';

    $dati.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
    $dati.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
    $dati.='<input type="submit" name="delete_selected" id="delete_selected" value="Elimina Selezionati" /></div>';
    
    $dati.="<a title='Aggiungi vino' class='' href='./add_wine.php' tabindex='' accesskey=''>Aggiungi Vino</a>";

    $dati.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Nome</div>
                            <div class="wines_td">Denominazione</div>
                            <div class="wines_td">Tipologia</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td modify_column">Modifica</div>
                            <div class="wines_td remove_column">Elimina</div>
                    </div>';

    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $dati.="<div class='wines_tr'>";
            $dati.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['id_wine'];
            if(isset($_POST['all_selected'])) $dati.="' checked='checked";
            $dati.="'></div>";
            $dati.="<div class ='wines_td'>".$row['nome']."</div>";
            $dati.="<div class ='wines_td'>".$row['denominazione']."</div>";
            $dati.="<div class ='wines_td'>".$row['tipologia']."</div>";
            $dati.="<div class ='wines_td'>".$row['annata']."</div>";
            $dati.="<div class ='wines_td modify_column'><a title='Modifica vino' class='' href='./modify_wine.php?idwine=".$row['id_wine']."' tabindex='' accesskey=''>Modifica</a></div>";
            $dati.="<div class ='wines_td remove_column'><a title='Elimina vino' class='' href='./delete_wine.php?wines=".$row['id_wine']."' tabindex='' accesskey=''>X</a></div>";
            $dati.="</div>";
        }
    else $dati.="<h2>Non sono presenti vini.</h2>";
    $dati.="</form>";
}


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  

if(!isset($section)) $search_wine = file_get_contents("../html/search_wine.html");
else $search_wine='';
$pagina = str_replace("[SEARCH_WINE]", $search_wine, $pagina);

$pagina = str_replace("[ANNATA]", $annata, $pagina);
$pagina = str_replace("[TIPOLOGIA]", $tipologia, $pagina);
$pagina = str_replace("[ORDINE]", $ordine, $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);
mysqli_close($conn);
?>
