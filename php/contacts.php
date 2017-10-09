<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

$stampa='';
$error='';
$info='';

/*
if(!empty($_COOKIE['error'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['error']."</h1><br></br>";
    setcookie('error',null);
}
if(!empty($_COOKIE['info'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['info']."</h1><br></br>";
    setcookie('info',null);
}
*/

if(!empty($_COOKIE['info'])){
    $info.="<h1 id='info_message'>".$_COOKIE['info']."</h1><br></br>";
    setcookie('info',null);
}

if(!empty($_COOKIE['error'])){
    $error.="<h1 id='error_message'>".$_COOKIE['error']."</h1>";
    setcookie('error',null);
}

if(!empty($_POST['email']) ){
    //controllo che la password e la mail inseriti rispetti le policy
    if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i",$_POST['email']))
    {
        setcookie('error',"Formato mail non valido");
        header("Location:contacts.php");
    } else if(empty($_POST['object'])){
        setcookie('error',"Inserire oggetto");
        header("Location:contacts.php");
        
    } else if(empty($_POST['msg'])){
        setcookie('error',"Inserire messaggio");
        header("Location:contacts.php");
        
    } else {
        $email= "info@winenot.it";
        $header= "From: ".$_POST['email'].">\n";
        $header .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
        $header .= "Content-Transfer-Encoding: 7bit\n\n";
        $subject= "WineNot.it - ";
        $subject.=$_POST['object'];
        $mess_invio="<html><body>";
        $mess_invio.=$_POST['msg'];
        $mess_invio.='</body></html>';
        
        //invio email
        if(mail($email, $subject, $mess_invio, $header)){
            $info.="<h1 id='info_message'>Email inviata con successo. Grazie per averci contattato!</h1>";
            unset($_POST); //elimino le variabili post
        }
    }
}
else {
    //    $pagina3 = file_get_contents("./html/form_help.html");
}


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
if(!empty($_SESSION['id'])) 
    $stampa = "<li><a title='Area Riservata' class='' href='../php/admin_panel.php' tabindex='' acceskey=''>Area Riservata</a></li>
               <li><a title='Esci dall'Area Riservata class='' href='../index.php?esci=1' tabindex='' accesskey='q'>Esci</a></li>";
else $stampa = "<li><a title='Area Riservata' class='' href='../php/login.php' tabindex='' acceskey=''>Area Riservata</a></li>";
$pagina = file_get_contents("../html/contacts.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[ERRORE]", $error, $pagina);
$pagina = str_replace("[INFO]", $info, $pagina);
echo str_replace("[AREA_RISERVATA]", $stampa, $pagina);
mysqli_close($conn);
?>
