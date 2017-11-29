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
$annata='';
$tipologia='';
$ordine='';
$improved_search='';

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

    $wines = isset($_POST['wines']) ? $_POST['wines'] : array();
    if (!count($wines)) {
        setcookie('error',"Selezionare almeno un elemento");
        header("Location: admin_wines.php");
    }   
    else{
        //per poter passare e poter usare un array tramite url posso ricorrere a due metodi:  serialize/unserialize o l'utilizzo di http_build_query che crea un url molto più lungo perchè inserisce ogni elemento singolarmente in questo modo key[indice]=valore
        header("Location: delete_wine.php?wines=".serialize($wines));
    }
}


//SELECT ANNATA NEL FORM
$sql = "SELECT annata FROM vini GROUP BY annata ORDER BY annata";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $annata.="<option value='".$row['annata']."'";
        if(!empty($_GET['annata']) && $_GET['annata']==$row['annata']) $annata.=" selected='selected'";
        $annata.=">".$row['annata']."</option>";
    }

//SELECT TIPOLOGIA NEL FORM
$array_tipologie=array('bianco','rosso','nero','ros&egrave');
$num_elementi=count($array_tipologie);
for($i=0 ; $i<$num_elementi ; $i++){
    $tipologia.="<option value='".$array_tipologie[$i]."'";
    if(!empty($_GET['tipologia']) && entityAccentedVowels($_GET['tipologia'])==$array_tipologie[$i]) $tipologia.=" selected='selected'";
    $tipologia.=">".$array_tipologie[$i]."</option>";
}

//SELECT ORDINE NEL FORM
$array_ordine=array('nome','annata','tipologia','gradazione','formato');
$num_elementi=count($array_ordine);
for($i=0 ; $i<$num_elementi ; $i++){
    $ordine.="<option value='".$array_ordine[$i]."'";
    if(!empty($_GET['ordine']) && $_GET['ordine']==$array_ordine[$i]) $ordine.=" selected='selected'";
    $ordine.=">".$array_ordine[$i]."</option>";
}

$text_search = 'vini';


//STAMPA I VINI SECONDO I PARAMETRI DI RICERCA
if(!empty($_GET['annata']) && !empty($_GET['tipologia']) && !empty($_GET['ordine'])){

    if(!empty($_GET['search'])){
        //chiamo la funzione in lib.php che controlla il testo inserito. (controllare ricerca su homie)

        // rendo tutto in minuscolo
        $search = strtolower($_GET['search']);

        // pulisco la stringa
        $search = cleanInput($search);

        $counter=0;
        while(!empty($search[$counter])) {

            if($counter>0) {
                $text_search = "( SELECT vini.* FROM ".$text_search." WHERE ( vini.nome LIKE '%".$search[$counter]."%' OR vini.denominazione LIKE '%".$search[$counter]."%' OR vini.tipologia LIKE '%".$search[$counter]."%' OR vini.vitigno LIKE '%".$search[$counter]."%' OR vini.gradazione LIKE '%".$search[$counter]."%' ) ) AS vini";
            }
            else{
                $text_search = "( SELECT vini.* FROM vini WHERE ( vini.nome LIKE '%".$search[$counter]."%' OR vini.denominazione LIKE '%".$search[$counter]."%' OR vini.tipologia LIKE '%".$search[$counter]."%' OR vini.vitigno LIKE '%".$search[$counter]."%' OR vini.gradazione LIKE '%".$search[$counter]."%' ) ) AS vini";
            }

            $counter++;

        }
    }



    if($_GET['annata']!='All') {
        $improved_search.=" WHERE annata='".$_GET['annata']."'";
    }

    if($_GET['tipologia']!='All'){
        if(!empty($improved_search)) $improved_search.=" AND tipologia='".entityAccentedVowels($_GET['tipologia'])."'";
        else {
            $improved_search.=" WHERE tipologia='".entityAccentedVowels($_GET['tipologia'])."'";
        }
    }


    //STAMPA I VINI 
    $sql = "SELECT vini.* FROM ".$text_search.$improved_search." ORDER BY ".$_GET['ordine'];

}
//STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
else $sql = "SELECT vini.* FROM vini";


$result=mysqli_query($conn,$sql);


$dati.='<form action="admin_wines.php" method="post">';

$dati.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
$dati.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
$dati.='<input type="submit" name="delete_selected" id="delete_selected" value="Elimina Selezionati" /></div>';

$dati.="<a title='Aggiungi vino' class='' href='./add_wine.php' tabindex='' accesskey=''>Aggiungi Vino</a>";

$dati.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Nome</div>
                            <div class="wines_td">Denominazione</div>
                            <div class="wines_td">Tipologia</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td modify_column">Modifica</div>
                            <div class="wines_td remove_column">Elimina</div>
                    </div>';

if(mysqli_num_rows($result)!=0)
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $dati.="<div class='wines_tr'>";
        $dati.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['id_wine'];
        if(isset($_POST['all_selected'])) $dati.="' checked='checked";
        $dati.="'></div>";
        $dati.="<div class ='wines_td'>".$row['nome']."</div>";
        $dati.="<div class ='wines_td'>".$row['denominazione']."</div>";
        $dati.="<div class ='wines_td'>".$row['tipologia']."</div>";
        $dati.="<div class ='wines_td'>".$row['annata']."</div>";
        $dati.="<div class ='wines_td modify_column'><a title='Modifica vino' class='' href='./modify_wine.php?idwine=".$row['id_wine']."' tabindex='' accesskey=''>Modifica</a></div>";
        $dati.="<div class ='wines_td remove_column'><a title='Elimina vino' class='' href='./delete_wine.php?wines=".$row['id_wine']."' tabindex='' accesskey=''>X</a></div>";
        $dati.="</div>";
    }
else $dati.="<h2>Non sono presenti vini.</h2>";
$dati.="</form>";


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
$search_wine = file_get_contents("../html/search_wine.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[SEARCH_WINE]", $search_wine, $pagina);
$pagina = str_replace("[ANNATA]", $annata, $pagina);
$pagina = str_replace("[TIPOLOGIA]", $tipologia, $pagina);
$pagina = str_replace("[ORDINE]", $ordine, $pagina);
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $dati, $pagina);
mysqli_close($conn);
?>
