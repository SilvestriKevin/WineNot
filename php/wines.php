<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

$vini='';
$annata='';
$tipologia='';
$ordine='';
$improved_search='';
$lista='';

if(!empty($_COOKIE['error'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['error']."</h1><br></br>";
    setcookie('error',null);
}
if(!empty($_COOKIE['info'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['info']."</h1><br></br>";
    setcookie('info',null);
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
$array_tipologie=array('bianco','rosso','ros&egrave;');
$num_elementi=count($array_tipologie);
for($i=0 ; $i<$num_elementi ; $i++){
    $tipologia.="<option value='".$array_tipologie[$i]."'";
    if(!empty($_GET['tipologia']) && entityAccentedVowels($_GET['tipologia'])==$array_tipologie[$i]) $tipologia.=" selected='selected'";
    $tipologia.=">".$array_tipologie[$i]."</option>";
}

//SELECT ORDINE NEL FORM
$array_ordine=array('nome','annata','tipologia','gradazione');
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
        //chiamo la funzione in lib.php che controlla il testo inserito.

        // pulisco la stringa
        $search = cleanInput($_GET['search']);

        $counter=0;
        while(!empty($search[$counter])) {

            if($counter>0) {
                $text_search = "( SELECT vini.* FROM ".$text_search." WHERE ( vini.nome LIKE '%".$search[$counter]."%' OR vini.denominazione LIKE '%".$search[$counter]."%' OR vini.tipologia LIKE '%".$search[$counter]."%' OR vini.vitigno LIKE '%".$search[$counter]."%' OR vini.gradazione LIKE '%".$search[$counter]."%' OR vini.annata LIKE '%".$search[$counter]."%' ) ) AS vini";
            }
            else{
                $text_search = "( SELECT vini.* FROM vini WHERE ( vini.nome LIKE '%".$search[$counter]."%' OR vini.denominazione LIKE '%".$search[$counter]."%' OR vini.tipologia LIKE '%".$search[$counter]."%' OR vini.vitigno LIKE '%".$search[$counter]."%' OR vini.gradazione LIKE '%".$search[$counter]."%' OR vini.annata LIKE '%".$search[$counter]."%' ) ) AS vini";
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

    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vini.="<li><a title='".$row['nome']."' href='../php/wine.php?id_wine=".$row['id_wine']."' tabindex=''><div id='specific_result' class='specific_wine'><img alt='' src='../img/".$row['id_wine'].".png'/><ul>";
            $vini.="<li><label>Nome: </label>".$row['nome']."</li>";
            $vini.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
            $vini.="<li><label>Annata: </label>".$row['annata']."</li>";
            $vini.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
            $vini.="</ul></div></a></li>";
        }
    else $vini.="<li><h2>Non sono presenti vini per questa ricerca. Riprova cambiando i parametri.</h2></li>";
}
else {
    //STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
    $sql = "SELECT vini.* FROM vini";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vini.="<li><a title='".$row['nome']."' href='../php/wine.php?id_wine=".$row['id_wine']."' tabindex=''><div id='specific_result' class='specific_wine'><img alt='' src='../img/".$row['id_wine'].".png'/><ul>";
            $vini.="<li><label>Nome: </label>".$row['nome']."</li>";
            $vini.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
            $vini.="<li><label>Annata: </label>".$row['annata']."</li>";
            $vini.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
            $vini.="</ul></div></a></li>";
        }
    else $vini.="<li><h2>Non sono presenti vini.</h2></li>";
}


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/wines.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[ANNATA]", $annata, $pagina);
$pagina = str_replace("[TIPOLOGIA]", $tipologia, $pagina);
$pagina = str_replace("[ORDINE]", $ordine, $pagina);
echo str_replace("[VINI]", $vini, $pagina);
mysqli_close($conn);
?>
