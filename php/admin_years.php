<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once('../include/config.php');

//controllo se è settata la session, altrimenti si viene riportati alla pagina iniziale
if (!isset($_SESSION['id'])) {
    header('Location: ../index.html');
}

//se il cookie è settato, lo unsetto
if (isset($_COOKIE['modifyYear'])) {
    unset($_COOKIE['modifyYear']);
    setcookie('modifyYear', '', time() - 3600);
}

//dichiarazione variabili
$dati='';
$info_errore='';

//stampo i messaggi informativi e/o di errore
if(!empty($_COOKIE['info'])){
    $info_errore.='<div id="top_message">'.$_COOKIE['info'].'</div>';
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.='<div id="top_message">'.$_COOKIE['error'].'</div>';
    setcookie('error',null);
}

//se è stato cliccato 'Elimina selezionate'
if(isset($_POST['delete_selected'])){

    $years = isset($_POST['years']) ? $_POST['years'] : array();
    
    //se non sono state selezionate annate stampo un messaggio d'errore
    if (!count($years))  $info_errore.='<div id="top_message">Selezionare almeno un&apos;annata</div>';   
    else{
        //per poter passare e poter usare un array tramite url posso ricorrere a due metodi:  serialize/unserialize o l'utilizzo di http_build_query che crea un url molto più lungo perchè inserisce ogni elemento singolarmente in questo modo key[indice]=valore
        header('Location: delete_year.php?years='.serialize($years));
    }
}

$dati.='<form onsubmit="return deleteSelected()" action="admin_years.php" method="post">';

$dati.='<div class="hide_content"><div class="select_admin_buttons"><input type="submit" class="admin_button all_selected" name="all_selected" 
        value="Seleziona Tutte" tabindex="11" />';
$dati.='<input type="submit" class="admin_button none_selected" name="none_selected" value="Deseleziona Tutte" tabindex="12" />';
$dati.='<input type="submit" class="admin_button delete_selected" name="delete_selected" value="Elimina Selezionate" tabindex="13"/>';
$dati.='<a title="Aggiungi Annata" href="./add_year.php" tabindex="14">Aggiungi Annata</a></div></div>';

$dati.='<div class="select_admin_buttons hide_js"><input type="button" class="admin_button all_selected" name="all_selected"  
        value="Seleziona Tutte" onclick="checkThemAll()" tabindex="11"/>';
$dati.='<input type="button" class="admin_button none_selected" name="none_selected" value="Deseleziona Tutte" onclick="uncheckThemAll()" tabindex="12"/>';
$dati.='<input type="submit" class="admin_button delete_selected" name="delete_selected" value="Elimina Selezionate" tabindex="13"/>';
$dati.='<a title="Aggiungi Annata" href="./add_year.php" tabindex="14">Aggiungi Annata</a></div>';

//STAMPA LE ANNATE
$sql = 'SELECT annate.* FROM annate';
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

    
    $counter_index = 15;    
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $dati.='<div class="admin_tr">';
        $dati.='<div class ="admin_td admin_years_checkbox_column"><input class="admin_years_checkbox admin_checkboxes"
         type="checkbox" name="years[]" value="'.$row['anno'];
        if(isset($_POST['all_selected'])) $dati.='" checked="checked';
        $dati.='" onclick="removeErrorMessage()" tabindex="'. $counter_index++.'"/></div>';
        $dati.='<div class ="admin_td admin_years_year_column">'.$row['anno'].'</div>';
        $dati.='<div class ="admin_td admin_years_quantity_column">'.$row['qualita'].'</div>';
        $dati.='<div class ="admin_td admin_years_best_column">';
        if($row['migliore'] == 0) $dati.='No';
        else $dati.='Si';
        $dati.='</div>';
        $dati.='<div class ="admin_td admin_years_modify_column"><a title="Modifica annata" 
        href="./modify_year.php?year='.$row['anno'].'" tabindex="'. $counter_index++.'">Modifica</a></div>';
        $dati.='<div class ="admin_td admin_years_remove_column"><a title="Elimina annata" 
         href="./delete_year.php?years='.$row['anno'].'" tabindex="'. $counter_index++.'">X</a></div>';
        $dati.='</div>';
    }
}
else {
    $dati.='<h2 id="no_elements">Non sono presenti annate.</h2>';
}

$dati.='</form>';


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents('../html/admin_panel.html');

//tolgo il link della pagina
$pagina = str_replace('<a title="gestione annate" href="admin_years.php" tabindex="4" accesskey="a">Gestione Annate</a>', 
'Gestione Annate', $pagina);

//rimpiazzo i segnaposto e stampo in output la pagina  
$pagina = str_replace('[SEARCH_WINE]', '', $pagina);
$pagina = str_replace('[INFO/ERRORE]', $info_errore, $pagina);
echo str_replace('[DATI]', $dati, $pagina);

//chiudo la connessione
mysqli_close($conn);
?>
