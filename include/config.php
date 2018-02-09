<?php

//parametri di connessione
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "winenot";

//connessione al DBMS
$conn = mysqli_connect($host, $user, $pass, $dbname);

//verifica su eventuali errori di connessione
if (!$conn) {
    header("Location: ../index_nodb.html");
}
