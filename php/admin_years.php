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


if(isset($_POST['delete_selected'])){

    $years = isset($_POST['years']) ? $_POST['years'] : array();
    if (!count($years)) {
        setcookie('error',"Selezionare almeno un elemento");
        header("Location: admin_years.php");
    }   
    else{
        //per poter passare e poter usare un array tramite url posso ricorrere a due metodi:  serialize/unserialize o l'utilizzo di http_build_query che crea un url molto più lungo perchè inserisce ogni elemento singolarmente in questo modo key[indice]=valore
        header("Location: delete_year.php?years=".serialize($years));
    }
}

$dati.='<form action="admin_years.php" method="post">';

$dati.='<div id="select_admin_buttons"><input type="submit"  class="admin_button name="all_selected" id="all_selected" value="Seleziona Tutti" />';
$dati.='<input type="submit" class="admin_button name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
$dati.='<input type="submit" class="admin_button name="delete_selected" id="delete_selected" value="Elimina Selezionati" />';
$dati.="<a title='Aggiungi Annata' class='' href='./add_year.php' tabindex='' accesskey=''>Aggiungi Annata</a></div>";

//STAMPA LE ANNATE
$sql = "SELECT annate.* FROM annate";
$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)!=0){
    $dati.='<div class="admin_tr" id="admin_header">
                            <div id="menu_select" class="admin_td">Selezione</div>
                            <div class="admin_td">Annata</div>
                            <div class="admin_td">Qualit&agrave;</div>
                            <div class="admin_td">Migliore</div>
                            <div class="admin_td modify_column">Modifica</div>
                            <div class="admin_td remove_column">Elimina</div>
                        </div>';
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $dati.="<div class='admin_tr'>";
        $dati.="<div class ='admin_td admin_years_checkbox_column'><input id='admin_years_checkbox' type='checkbox' name='years[]' value='".$row['anno'];
        if(isset($_POST['all_selected'])) $dati.="' checked='checked";
        $dati.="'></div>";
        $dati.="<div class ='admin_td admin_years_year_column'>".$row['anno']."</div>";
        $dati.="<div class ='admin_td admin_years_quantity_column'>".$row['qualita']."</div>";
        $dati.="<div class ='admin_td admin_years_best_column'>";
        if($row['migliore'] == 0) $dati.="No";
        else $dati.="Si";
        $dati.="</div>";
        $dati.="<div class ='admin_td admin_years_modify_column'><a title='Modifica vino' class='' href='./modify_year.php?year=".$row['anno']."' tabindex='' accesskey=''>Modifica</a></div>";
        $dati.="<div class ='admin_td admin_years_remove_column'><a title='Elimina annata' class='' href='./delete_year.php?years=".$row['anno']."' tabindex='' accesskey=''>X</a></div>";
        $dati.="</div>";
    }
}
else {
    $dati.="<h2>Non sono presenti annate.</h2>";
}

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
