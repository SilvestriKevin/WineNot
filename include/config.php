<?php
/*
  Connessione al DBMS e selezione del database.
*/
# blocco dei parametri di connessione
// nome di host
$host = "localhost";
// username dell'utente in connessione
$user = "root";
// password dell'utente
$pass = "";
// nome del database
$dbname = "winenot";

# stringa di connessione al DBMS
// istanza dell'oggetto della classe MySQLi
$conn = mysqli_connect($host, $user, $pass, $dbname);

// verifica su eventuali errori di connessione
if (!$conn) die('Connessione fallita: '.mysqli_connect_error()); 

?>
