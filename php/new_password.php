<?php
//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

//il controllo del get evita errori di pagina
if(isset($_GET['hash'])){
  $hash=$_GET['hash'];
  $id=substr($hash, 32);
  $password_old=substr($hash, 0, 32);
  $password=random(8); //nuova password di 8 caratteri
  //controllo che i valori dell’hash corrispondano ai valori salvati nel database
    $sql="SELECT * FROM utenti WHERE id_user=".$id." AND password='".$password_old."'";
  $result=mysqli_query($conn,$sql);
  if(mysqli_num_rows($result)>0){ 
    $row=mysqli_fetch_array($result, MYSQLI_ASSOC);
    $email=$row['email'];
    //salvo la nuova password al posto della vecchia (in md5)
        $sql="update utenti set password='".md5($password)."' where id_user=".$id." and password='".$password_old."'";
    $result=mysqli_query($conn,$sql);
    $header= "From: WineNot.it <info@WineNot.it>\n";
    $header .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
    $header .= "Content-Transfer-Encoding: 7bit\n\n";
    $subject= "WineNot.it - Nuova password utente";
    $mess_invio="<html><body>";
    $mess_invio.=" La sua nuova password utente &egrave; ".$password."<br /> Ora puoi accedere all'area <a href=\"http://localhost/WineNot/php/login.php\">Login</a>. ";
    $mess_invio.='</body><html>';
    if(mail($email, $subject, $mess_invio, $header)){
    echo "La password è stata cambiata con successo. Controlla la tua email.<br /><br />";
    }
  }
} 
?>
