<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");



//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

$info_errore='';
if(!empty($_COOKIE['info'])){
    $info_errore.="<h1>".$_COOKIE['info']."</h1>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<h1>".$_COOKIE['error']."</h1>";
    setcookie('error',null);
}


$year='';
$year=$_GET['year'];



if(isset($_POST['anno']) && isset($_POST['descrizione']) && isset($_POST['qualita'])) {
    // controllo che non siano stati lasciati spazi vuoti all'interno di anno e che tutti i campi siano non vuoti.

    if(!empty($_POST['anno']) && !empty($_POST['descrizione']) && !empty($_POST['qualita'])) {
        //dichiaro le variabili

        $anno = $_POST['anno'];
        $descrizione = $_POST['descrizione'];
        $qualita = $_POST['qualita'];

        // controllo che l'anno sia del formato giusto

        if(is_numeric($anno) && strlen($anno)==4 && !preg_match("/^(\s)+$/",$anno)) {
            // controllo che l'anno non sia già presente all'interno del database

            $sql = "SELECT anno FROM annate WHERE anno='".$anno."'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) != 0){
                setcookie('error',"L'anno inserito esiste gi&agrave;"); 
                header("Location: modify_year.php?year=".$anno."");
            } else {
                // inserisco i dati nel database
                
                if(!isset($_POST['migliore']))
                {   
                    $sql = "UPDATE annate SET anno=".$anno.", descrizione='".$descrizione."', qualita='".$qualita."', migliore=0 WHERE anno=".$year."";
                }
                else { 
                    $sql = "UPDATE annate SET anno=".$anno.", descrizione='".$descrizione."', qualita='".$qualita."' WHERE anno=".$year."";
                }
                //controllo la connessione
                if (mysqli_query($conn, $sql) == TRUE) {
                    setcookie('info',"Modifica avvenuta con successo.");
                    header("Location: admin_panel.php");
                } else {
                    setcookie('error',"Si &egrave; verificato un errore. La preghiamo di riprovare");
                    header("Location: modify_year.php?year=".$anno."");
                }
            }
        } else {
            setcookie('error','Anno non è nel formato giusto.');
            header("Location: modify_year.php?year=".$anno.""); 
        }

    } else {
        if(!empty($_POST['save_year'])) {
        setcookie('error','Alcuni campi risultano vuoti.');
        header("Location: modify_year.php?year=".$anno.""); 
        }
    }
}

$sql = "SELECT * FROM annate WHERE anno='".$year."'";

echo $sql;

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result,MYSQL_ASSOC);

$annata='';

$annata.='<form action="modify_year.php" method="post">';

$annata.='<input type="hidden" name="year" value="'.$year.'" />';

if(mysqli_num_rows($result)!=0) {
    $annata.='<ul>';
    
    $annata.='<li><label>Anno: </label><input type="text" maxlength="4" name="anno" title="anno" value="'.$row['anno'].'"</li>';
    $annata.='<li><label>Descrizione: </label><input type="text" maxlength="30" name="descrizione" title="descrizione" value="'.$row['descrizione'].'"</li>';
    $annata.='<li><label>Qualit&agrave;: </label><input type="text" maxlength="30" name="qualita" title="qualita" value="'.$row['qualita'].'"</li>';
    
    $annata.='<li><label>Migliore: </label><input type="checkbox" maxlength="30" name="migliore" title="migliore"';
    
    if($row['migliore'] == 1)
        $annata.='checked="checked" /></li>';
    else $annata.='/></li>';
    
    $annata.='<li><input type="submit" name="save_year" id="" value="Salva" /></li>';
    $annata.='</ul>';
} else { $annata.='<h1>Ci sono dei problemi con il database.</h1>'; }


$annata.='</form>';



//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[VINI]", $annata, $pagina);
mysqli_close($conn);
?>
