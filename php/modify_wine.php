<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");



$vino='';
$info_errore='';

if(!empty($_COOKIE['info'])){
    $info_errore.="<h1>".$_COOKIE['info']."</h1>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<h1>".$_COOKIE['error']."</h1>";
    setcookie('error',null);
}

if(empty($_GET['idwine'])) {
    $id_wine = $_POST['idwine'];
} else $id_wine = $_GET['idwine'];
// prendo il valore chiave del vino che voglio andare a modificare

// controllo che non siano stati lasciati spazi vuoti e poi modifico all'interno del database i campi dati richiesti
$error='';

if(!empty($_POST['save_wine'])) {

    if(!empty($_POST['annata']) &&
       !empty($_POST['nome']) &&
       !empty($_POST['tipologia']) &&
       !empty($_POST['vitigno']) &&
       !empty($_POST['denominazione']) &&
       !empty($_POST['gradazione']) &&
       !empty($_POST['formato']) &&
       !empty($_POST['descrizione']) &&
       !empty($_POST['abbinamento']) &&
       !empty($_POST['degustazione'])) {

        $nome = $_POST['nome'];
        $tipologia = $_POST['tipologia'];
        $descrizione = $_POST['descrizione'];
        $denominazione = $_POST['denominazione'];
        $annata = $_POST['annata'];
        $vitigno = $_POST['vitigno'];
        $abbinamento = $_POST['abbinamento'];
        $degustazione = $_POST['degustazione'];
        $gradazione = $_POST['gradazione'];
        $formato = $_POST['formato'];
        // siccome tutti i campi non sono vuoti allora potrò procedere con i controlli all'interno del database

        // controllo che il campo annata sia giusto
        if(!is_numeric($annata) || strlen($annata)!=4 || preg_match("/^(\s)+$/",$annata))
            $error.='Anno non è nel formato giusto./n';

        // controllo gradazione

        if(strlen($gradazione) !=4 ||!preg_match("/\d{2}\.\d/",strval($gradazione)) ||
           preg_match("/^(\s)+$/",$gradazione))
            $error.='Gradazione non è nel formato giusto./n';

        // controllo formato

        if(strlen($formato) != 4 || !preg_match("/\d\.\d{2}/",$formato) || preg_match("/^(\s)+$/",$formato))
            $error.='Formato non è nel formato giusto./n';



        // se ho caricato un'immagine, dò la possibilità di poterla cambiare

        if(!empty($_FILES['wine_img']) &&
           $_FILES['wine_img']['type'] == "image/png") {
            $file = $_FILES['wine_img'];

            if($file['error'] != UPLOAD_ERR_OK && !is_uploaded_file($file['tmp_name'])) {
                $error.="C'&egrave; stato un problema con il caricamento dell'immagine. La preghiamo di riprovare./n";
            }
        }



        // se quei dati sopra sono giusti 
        // posso dunque procedere con il cambiamento dei dati (cercando di stare attenti a non modificare un vino in un altro vino uguale)


        if(!empty($error)) { // ci sono dunque stati dei problemi
            setcookie('error',$error);
            header("Location: modify_wine.php?idwine=".$id_wine."");
        } else { // posso ora controllare che questo vino non esista già escludendo il vino attuale per evitare problemi


            $sql = "SELECT * FROM vini WHERE annata='".$annata."' AND nome='".$nome."' AND tipologia='".$tipologia."' AND denominazione='".$denominazione."' AND id_wine != '".$id_wine."'";

            $result = mysqli_query($conn,$sql);

            if(mysqli_num_rows($result) != 0) {
                setcookie('error', "Un vino con queste informazioni &egrave; stato gi&agrave; inserito.");
                header('Location: modify_wine.php?idwine='.$id_wine.'');

            } else {
                // posso ora aggiornare il mio vino all'interno del database

                $sql = "UPDATE vini SET nome='".$nome."', tipologia='".$tipologia."', descrizione='".$descrizione."', denominazione='".$denominazione."', annata='".$annata."', vitigno='".$vitigno."', abbinamento='".$abbinamento."', degustazione='".$degustazione."', gradazione='".$gradazione."', formato='".$formato."' WHERE id_wine='".$id_wine."'";

                $result = mysqli_query($conn,$sql);
                if($result) { // se c'è stata una modifica
                    
                    $file['name'] = $id_wine;
                    
                    if(move_uploaded_file($file['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/WineNot/img/".$file['name'].".png")) {
                    
                    setcookie('info','Modifica dei dati avvenuta con successo.');
                    header('Location: admin_wines.php');} else // il caricamento file non è andato a buon fine, tuttavia la query ha fatto il suo dovere 
                    {
                        setcookie("error","Il caricamento dell'immagine non &egrave; andato a buon fine. Tuttavia il gli altri dati sono stati aggiornati correttamente.");
                        header("Location: modify_wine.php?idwine=".$id_wine."");
                }
                } else { // la modifica non è andata a buon fine
                    setcookie('error',"Si &egrave; verificato un problema con la modifica dei dati all'interno del database.");
                    header('Location: modify_wine.php?idwine='.$id_wine.'');
                }
            }

        }


    } else  $error.="Alcuni campi dati sono stati lasciati vuoti.";
}


if(!empty($error)) setcookie('error',$error);



//FORM DATI VINO

$sql = "SELECT * FROM vini WHERE id_wine='".$id_wine."'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result,MYSQL_ASSOC);

$annata='';

$vino.='<h3 id="admin_title">Modifica il vino</h3>';

$vino.='<form id="panel_admin_form" action="modify_wine.php" method="post">';
$vino.='<input type="hidden" name="idwine" value="'.$id_wine.'" />';

if(mysqli_num_rows($result)!=0) {
    $vino.='<ul>';
    $annata = $row['annata'];

    // aggiungo tutte le annate 

    $vino.='<li><label>Annata: </label><select name="annata">';
    $sql = "SELECT annata as anno FROM vini GROUP BY anno ORDER BY anno";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=0)
        while($subrow = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vino.="<option value='".$subrow['anno']."'";
            // faccio in modo che venga selezionata l'annata giusta per questo vino
            if($annata == $subrow['anno']) $vino.=" selected='selected'";
            $vino.=">".$subrow['anno']."</option>";
        }
    $vino.='</select><a title="Aggiungi annata" class="" href="./add_year.php" tabindex="" accesskey="">Aggiungi Annata</a></li>';
    $vino.='<input type="hidden" name="action" value="upload" />';
    $vino.='<li><label>Nome: </label><input type="text" maxlength="30" name="nome" title="nome" value="'.$row['nome'].'"</li>';
    $vino.='<li><label>Tipologia: </label><input type="text" maxlength="30" name="tipologia" title="tipologia" value="'.$row['tipologia'].'"</li>';
    $vino.='<li><label>Vitigno: </label><input type="textarea" maxlength="30" name="vitigno" title="vitigno" value="'.$row['vitigno'].'"</li>';
    $vino.='<li><label>Denominazione: </label><input type="text" maxlength="30" name="denominazione" title="denominazione" value="'.$row['denominazione'].'"</li>';
    $vino.='<li><label>Gradazione(%): </label><input type="text" maxlength="30" name="gradazione" title="gradazione" value="'.$row['gradazione'].'"</li>';
    $vino.='<li><label>Formato(l)   : </label><input type="text" maxlength="30" name="formato" title="formato" value="'.$row['formato'].'"</li>';
    $vino.='<li><label>Descrizione: </label><input type="textarea"  name="descrizione" title="descrizione" value="'.$row['descrizione'].'"</li>';
    $vino.='<li><label>Abbinamento: </label><input type="textarea"  name="abbinamento" title="abbinamento" value="'.$row['abbinamento'].'"</li>';
    $vino.='<li><label>Degustazione: </label><input type="textarea"  name="degustazione" title="degustazione" value="'.$row['degustazione'].'"</li>';
    $vino.="<li><label>Immagine attuale: </label><img id='modify_wine_img' alt='immagine del vino' src='../img/".$row['id_wine'].".png' /></li>
    <li><label>Cambia immagine: </label><input id='select_file' type='file' name='wine_img'/></li>";
    $vino.='<input type="submit" class="search_button" name="save_wine" id="save_modify_wine" value="Salva" />';
    $vino.='</ul>';
    
    // provo a modificare i campi dati
} else $vino.='<h2>Non ho trovato informazioni riguardo a questo vino.</h2>';


$vino.='</form>';



//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  

$pagina = str_replace("[SEARCH_WINE]", '', $pagina);
$pagina = str_replace("[INFO/ERRORE]",$error,$pagina);
echo str_replace("[DATI]", $vino, $pagina);
mysqli_close($conn);
?>
