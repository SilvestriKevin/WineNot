<?php
//inclusione file di connessione
include_once("../include/config.php");

/* PARTE DELL’INVIO EMAIL. Si controlla che l'email (=user) sia presente nel db. Estraggo quindi id e password dell'utente e li unisco in un'unica stringa ($hash) da passare nel $_GET. La stringa su cui cliccare è inviata per email, come conferma, e rinvia al file “nuova_password.php”. */
  $errore=0; //variabile di controllo errori (se rimane a 0 non ci sono errori)
  if($_POST['email']==""){
    $errore=1;
  }else{
        $sql="select id_user as id, password from utenti where email='".$_POST['email']."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
      $row=mysqli_fetch_array($result, MYSQLI_ASSOC);
      //l’hash ci servirà per recuperare i dati utente e confermare la richiesta
      //la password nel database si presume criptata, con md5 o altro algoritmo
      //al posto di questi due dati, se ne possono usare altri legati all’utente, purché univoci
      $hash=$row['password']."".$row['id'];
    }else
      $errore=1;
  }
//se non ci sono stati errori, invio l’email all’utente con il link da confermare
  if($errore==0){
    $header= "From: WineNot.it <info@WineNot.it>\n";
    $header .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
    $header .= "Content-Transfer-Encoding: 7bit\n\n";
    $subject= "WineNot.it - Conferma nuova password utente";
    $mess_invio="<html><body>";
    $mess_invio.=" Clicca sul <a href=\"http://localhost/WineNot/php/new_password.php?hash=".$hash."\">link</a> per confermare la nuova password.<br /> Se il link non &egrave; visibile, copia la riga qui sotto e incollala sul tuo browser: <br /> http://localhost/WineNot/php/new_password.php?hash=".$hash." ";
    $mess_invio.='</body><html>';
    //invio email
    if(mail($_POST['email'], $subject, $mess_invio, $header)){
      echo "<h1 id='error_message'>Email inviata con successo. Controlla la tua email.</h1>";
      unset($_POST); //elimino le variabili post, in modo che non appaiano nel form
    }
  }
?>
