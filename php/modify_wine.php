<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");



$vino='';

if(!empty($_COOKIE['info'])){
    $info_errore.="<h1>".$_COOKIE['info']."</h1>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<h1>".$_COOKIE['error']."</h1>";
    setcookie('error',null);
}



$id_wine=$_GET['idwine'];

$sql = "SELECT vini.* FROM vini WHERE id_wine='".$id_wine."'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result,MYSQL_ASSOC);

$annata='';

$vino.='<form action="modify_wine.php?idwine="'.$id_wine.'" method="post">';

if(mysqli_num_rows($result)!=0) {
    $vino.='<ul>';
    $annata = $row['annata'];
    
    // aggiungo tutte le annate 
    
    $vino.='<li><label>Annata: </label><select>';
    $sql = "SELECT annata as anno FROM vini GROUP BY anno ORDER BY anno";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($subrow = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vino.="<option value='".$subrow['anno']."'";
            // faccio in modo che venga selezionata l'annata giusta per questo vino
            if($annata == $subrow['anno']) $vino.=" selected='selected'";
            $vino.=">".$subrow['anno']."</option>";
        }
    $vino.='</select><a title="Aggiungi utente" class="" href="./add_year.php" tabindex="" accesskey="">Aggiungi Annata</a></li>';

    $vino.='<li><label>Nome: </label><input type="text" maxlength="30" name="nome" title="nome" value="'.$row['nome'].'"</li>';
    $vino.='<li><label>Tipologia: </label><input type="text" maxlength="30" name="tipologia" title="tipologia" value="'.$row['tipologia'].'"</li>';
    $vino.='<li><label>Vitigno: </label><input type="textarea" maxlength="30" name="vitigno" title="vitigno" value="'.$row['vitigno'].'"</li>';
    $vino.='<li><label>Denominazione: </label><input type="text" maxlength="30" name="denominazione" title="" value="'.$row['denominazione'].'"</li>';
    $vino.='<li><label>Gradazione(%): </label><input type="text" maxlength="30" name="gradazione" title="gradazione" value="'.$row['gradazione'].'"</li>';
    $vino.='<li><label>Formato(l)   : </label><input type="text" maxlength="30" name="formato" title="formato" value="'.$row['formato'].'"</li>';
    $vino.='<li><label>Descrizione: </label><input type="textarea"  name="descrizione" title="descrizione" value="'.$row['descrizione'].'"</li>';
    $vino.='<li><label>Abbinamento: </label><input type="textarea"  name="abbinamento" title="abbinamento" value="'.$row['abbinamento'].'"</li>';
    $vino.='<li><label>Degustazione: </label><input type="textarea"  name="degustazione" title="degustazione" value="'.$row['degustazione'].'"</li>';
    $vino.='<li><input type="submit" name="save_profile" id="save_profile_modifications" value="Salva" /></li>';
    $vino.='</ul>';
} else 
    $vino.='<h1>Ci sono dei problemi con il database.</h1>';

$vino.='</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
echo str_replace("[VINI]", $vino, $pagina);
mysqli_close($conn);
?>
