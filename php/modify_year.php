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

if(empty($_GET['year'])) {
    $year = $_POST['anno'];
} else $year = $_GET['year'];



if(!empty($_POST['save_year'])) {
    // controllo che non siano stati lasciati spazi vuoti all'interno di anno e che tutti i campi siano non vuoti.
    
    if(!empty($_POST['descrizione']) &&
       !empty($_POST['qualita'])) {
    
    //dichiaro le variabili
    $descrizione = $_POST['descrizione'];
    $qualita = $_POST['qualita'];

    // controllo che l'anno sia del formato giusto

    //inserisco i dati nel database

    if($_POST['migliore'] == FALSE)
    {   
        $sql = "UPDATE annate SET descrizione='".$descrizione."', qualita='".$qualita."', migliore=0 WHERE anno=".$year."";
        
        $result = mysqli_query($conn,$sql);
    } else { 
        $sql = "UPDATE annate SET descrizione='".$descrizione."', qualita='".$qualita."', migliore=1 WHERE anno=".$year."";
        $result = mysqli_query($conn,$sql);
        
    }
    //controllo la connessione
    if ($result) {
        setcookie('info',"Modifica avvenuta con successo.");
        header("Location: admin_years.php");
    } else {
        echo $sql;
        setcookie('error',"Si &egrave; verificato un errore. La preghiamo di riprovare");
        header("Location: modify_year.php?year=".$year."");
    }

} else {
        setcookie('error','Alcuni campi risultano vuoti.');
        header("Location: modify_year.php?year=".$year.""); 
    }  
}


$sql = "SELECT * FROM annate WHERE anno='".$year."'";


$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result,MYSQL_ASSOC);

$annata='';

$annata.='<h3 id="admin_title">Modifica annata</h3>';

$annata.='<form id="panel_admin_form" action="modify_year.php" method="post">';

$annata.='<input type="hidden" name="save_year" value="'.$year.'" />';

if(mysqli_num_rows($result)!=0) {
    $annata.='<ul>';

    $annata.='<li><label>Anno: </label><input type="text" maxlength="4" name="anno" title="anno" value="'.$row['anno'].' "/></li>';
    $annata.='<li><label>Descrizione: </label></li><li><textarea id="textarea_modify_year" rows="5" cols="50" name="descrizione" title="descrizione">'.$row['descrizione'].'</textarea></li> ';
    $annata.='<li><label>Qualit&agrave;: </label><input type="text" maxlength="30" name="qualita" title="qualita" value="'.$row['qualita'].'"/></li>';

    $annata.='<li><label>Migliore: </label><input type="checkbox" maxlength="30" name="migliore" title="migliore"';

    if($row['migliore'] == 1)
        $annata.='checked="checked" /></li>';
    else $annata.='/></li>';

    $annata.='<li><input type="submit" class="search_button" name="save_year" id="save_modify_year" value="Salva" /></li>';
    $annata.='</ul>';
} else { $annata.='<h2>Ci sono dei problemi con il database.</h2>'; }


$annata.='</form>';



//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");

$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]",$info_errore,$pagina);
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[DATI]", $annata, $pagina);
mysqli_close($conn);
?>
