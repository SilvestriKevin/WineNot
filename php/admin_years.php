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


if(isset($_POST['delete_finally_selected'])){

    $years = isset($_POST['years']) ? $_POST['years'] : array();
    if (!count($years)) {
        setcookie('error',"Selezionare almeno un elemento");
        header("Location: admin_years.php");
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
            header("Location: admin_years.php");
        }
        else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
        header("Location: admin_years.php");
    }
}

if(isset($_POST['restore_selected'])){

    $years = isset($_POST['years']) ? $_POST['years'] : array();
    if (!count($years)) {
        setcookie('error',"Selezionare almeno un elemento");
        header("Location: admin_years.php");
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
            header("Location: admin_years.php");
        }
        else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
        header("Location: admin_years.php");
    }
}

$dati.='<form action="admin_years.php" method="post">';

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


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);
mysqli_close($conn);
?>
