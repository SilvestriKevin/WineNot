<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

$stampa='';
$vini='';
$annata='';
$tipologia='';
$improved_search='';

/*
if(!empty($_COOKIE['error'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['error']."</h1><br></br>";
    setcookie('error',null);
}
if(!empty($_COOKIE['info'])){
    $lista.="<h1 id='error_message'>".$_COOKIE['info']."</h1><br></br>";
    setcookie('info',null);
}
*/


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
$sql = "SELECT tipologia FROM vini GROUP BY tipologia ORDER BY tipologia";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $tipologia.="<option value='".$row['tipologia']."'";
        if(!empty($_GET['tipologia']) && $_GET['tipologia']==$row['tipologia']) $tipologia.=" selected='selected'";
        $tipologia.=">".$row['tipologia']."</option>";
    }


$text_search = '';
        

//STAMPA I VINI SECONDO I PARAMETRI DI RICERCA
if(!empty($_GET['annata']) && !empty($_GET['tipologia']) && !empty($_GET['ordine'])){
    
    if(!empty($_GET['search'])){
        //chiamo la funzione in lib.php che controlla il testo inserito. (controllare ricerca su homie)
        
        // rendo tutto in minuscolo
        $search = strtolower($_GET['search']);
        
        // pulisco la stringa
        $search = cleanInput($search);
        
        $counter=0;
        $text_search = " WHERE ";
        while(!empty($search[$counter])) {
            
            if($counter>0) {
                $text_search.=" OR ";
            }
            
            $text_search.= "vini.nome LIKE '%".$search[$counter]."%' OR vini.tipologia LIKE '%".$search[$counter]."%' OR vini.vitigno LIKE '%".$search[$counter]."%' OR vini.gradazione LIKE '%".$search[$counter]."%'";
           
            $counter++;
        }
    }
    
    
    
    if($_GET['annata']!='All') {
        if(!empty($_GET['search'])) {
            $improved_search.=" AND annata='".$_GET['annata']."'";
        } else {
            $improved_search.=" WHERE annata='".$_GET['annata']."'";
        }
    }
    
    if($_GET['tipologia']!='All'){
        if(!empty($improved_search)) $improved_search.=" AND tipologia='".$_GET['tipologia']."'";
        //else $improved_search.=" WHERE tipologia='".$_GET['tipologia']."'";
    }
    

    //STAMPA I VINI 
    $sql = "SELECT vini.nome, vini.tipologia,vini.vitigno,vini.gradazione,vini.foto, vini.id_wine FROM vini ".$text_search.$improved_search." ORDER BY ".$_GET['ordine'];
    
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vini.="<li><a title='".$row['nome']."' href='../php/wine.php?id_wine=".$row['id_wine']."' tabindex=''><div id='specific_result' class='specific_wine'><img alt='' src='../img/".$row['foto'].".png'/><ul>";
            $vini.="<li><label>Nome: </label>".$row['nome']."</li>";
            $vini.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
            $vini.="<li><label>Vitigno: </label>".$row['vitigno']."</li>";
            $vini.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
            $vini.="</ul></div></a></li>";
        }
    else $vini.="<li><h2>".$sql."Non sono presenti vini per questa ricerca. Riprova cambiando i parametri.</h2></li>";
}
else {
    //STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
    $sql = "SELECT vini.* FROM vini";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vini.="<li><a title='".$row['nome']."' href='../php/wine.php?id_wine=".$row['id_wine']."' tabindex=''><div id='specific_result' class='specific_wine'><img alt='' src='../img/".$row['foto'].".png'/><ul>";
            $vini.="<li><label>Nome: </label>".$row['nome']."</li>";
            $vini.="<li><label>Tipologia: </label>".$row['tipologia']."</li>";
            $vini.="<li><label>Vitigno: </label>".$row['vitigno']."</li>";
            $vini.="<li><label>Gradazione: </label>".$row['gradazione']."</li>";
            $vini.="</ul></div></a></li>";
        }
    else $vini.="<li><h2>Non sono presenti vini.</h2></li>";
}


//creazione della pagina web
//leggo il file e lo inserisco in una stringa
if(!empty($_SESSION['id'])) 
    $stampa = "<li><a title='Area Riservata' class='' href='../php/admin_panel.php' tabindex='' acceskey=''>Area Riservata</a></li>
               <li><a title='Esci dall'Area Riservata class='' href='../index.php?esci=1' tabindex='' accesskey='q'>Esci</a></li>";
else $stampa = "<li><a title='Area Riservata' class='' href='../php/login.php' tabindex='' accesskey=''>Area Riservata</a></li>";
$pagina = file_get_contents("../html/wines.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[ANNATA]", $annata, $pagina);
$pagina = str_replace("[TIPOLOGIA]", $tipologia, $pagina);
$pagina = str_replace("[VINI]", $vini, $pagina);
echo str_replace("[AREA_RISERVATA]", $stampa, $pagina);
mysqli_close($conn);
?>
