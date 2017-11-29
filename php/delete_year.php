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

//stampo i messaggi informativi e/o di errore
if(!empty($_COOKIE['info'])){
    $info_errore.="<li>".$_COOKIE['info']."</li>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<li>".$_COOKIE['error']."</li>";
    setcookie('error',null);
}

//se è settato $_POST['cancel'] significa che l'utente ha deciso di annullare l'eliminazione
if(!empty($_POST['cancel'])) header("Location: admin_years.php");

//in $_POST['years'] sono contenuti tutti gli id dei vini che si vogliono eliminare
//se è settata anche $_GET['delete_elements'] allora procedo all'eliminazione
else if(!empty($_POST['years']) && !empty($_POST['confirm'])){
    $years = $_POST['years'];
    $num_elem = count($years);
    $sql="DELETE FROM annate WHERE anno = '";
    for($i=0 ; $i<$num_elem ; $i++){
        if($i!=0) $sql.="' OR anno = '";
        $sql.=$years[$i];
    }
    $sql.="'";
    $result = mysqli_query($conn,$sql);
    
    
    
    //DA FARE controllo presenza vini di quella annata
    
    
    //controllo la connessione
    if ($result) {
        $message = "Eliminazione avvenuta con successo. ";
        if($num_elem == 1) $message .= "Eliminato 1 annata.";
        else $message .= "Eliminati ".$num_elem." annate.";
        setcookie('info',$message);
    }
    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");

    //ritorno in ogni caso alla gestione dei vini
    header("Location: admin_years.php");
}
//in $_GET['years'] sono contenuti tutti gli id dei vini che si vogliono eliminare
//se è settato solo $_GET['years'] allora mostro la richiesta di conferma per l'eliminazione
else if(!empty($_GET['years'])){
    $dati.='<form action="delete_year.php" method="post">';

    $dati.='<input type="submit" name="cancel" id="cancel" value="Annulla Eliminazione" /></div>';
    $dati.='<input type="submit" name="confirm" id="confirm" value="Conferma Eliminazione" /></div>';

    //controllo che nell'url abbia un array serializzato o un singolo dato
    //quindi provo a fare unserialize e se fallisce allora deduco di avere un dato unico
    //N.B.:inserire @ prima di una chiamata di funzione, evita che vengano mostrati errori che potrebbero essere lanciati da quella funzione e che potrebbero bloccare l'esecuzione del codice. === significa 'identico' mentre == significa 'uguale'
    if(($result = @unserialize($_GET['years'])) === false){
        $year = $_GET['years'];
        $sql="SELECT annate.* FROM annate WHERE anno = '".$year."'";
    }
    //altrimenti sono sicuro di avere un array serializzato 
    else {
        $years = unserialize($_GET['years']); //estrapolo i dati dall'array
        $num_elem = count($years);
        $sql="SELECT annate.* FROM annate WHERE anno = '";
        for($i=0 ; $i<$num_elem ; $i++){
            if($i!=0) $sql.="' OR anno = '";
            $sql.=$years[$i];
        }
        $sql.="'";
    }

    $result=mysqli_query($conn,$sql);

    $dati.='<div class="admin_tr" id="admin_header">
                            <div class="admin_td">Selezione</div>
                            <div class="admin_td">Annata</div>
                            <div class="admin_td">Qualit&agrave;</div>
                            <div class="admin_td">Migliore</div>
                    </div>';

    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $dati.="<div class='admin_tr'>";
            $dati.="<div class ='admin_td'><input type='checkbox' name='years[]' value='".$row['anno']."' checked='checked'></div>";
            $dati.="<div class ='admin_td'>".$row['anno']."</div>";
            $dati.="<div class ='admin_td'>".$row['qualita']."</div>";
            $dati.="<div class ='admin_td'>";
            if($row['migliore'] == 0) $dati.="No";
            else $dati.="Si";
            $dati.="</div>";
            $dati.="</div>";
        }
    else header("Location: admin_years.php");

    $dati.="</form>";
}
//se la variabile non è settata significa che è stato manomesso l'url, allora riporto l'utente alla pagina amministrazione
else header("Location: admin_years.php");

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);
mysqli_close($conn);
?>