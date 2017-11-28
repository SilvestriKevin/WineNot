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
    $info_errore.="<li>".$_COOKIE['info']."</li>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<li>".$_COOKIE['error']."</li>";
    setcookie('error',null);
}

// qualsiasi tipo di utente può aggiungere una nuova annata

$vino.='<form action="add_wine.php" method="post">';        
$vino.= '<fieldset id="register_fieldset"><ul>';

$vino.='<li><label>Annata: </label><select>';
$sql = "SELECT annata as anno FROM vini GROUP BY anno ORDER BY anno";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
    while($subrow = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $vino.="<option value='".$subrow['anno']."'>".$subrow['anno']."</option>";
    }


$vino.="</select><a title='Aggiungi annata' class='' href='./add_year.php' tabindex='' accesskey=''>Aggiungi Annata</a></li>
            <li><label>Nome: </label><input type='text' maxlength='30' name='nome' title='nome' </li>
            <li><label>Tipologia: </label><input type='text' maxlength='30' name='tipologia' title='tipologia' </li>
            <li><label>Vitigno: </label><input type='textarea' maxlength='30' name='vitigno' title='vitigno' </li>
            <li><label>Denominazione: </label><input type='text' maxlength='30' name='denominazione' title='denominazione' </li>
            <li><label>Gradazione(%): </label><input type='text' maxlength='30' name='gradazione' title='gradazione' </li>
            <li><label>Formato(l)   : </label><input type='text' maxlength='30' name='formato' title='formato' </li>
            <li><label>Descrizione: </label><input type='textarea'  name='descrizione' title='descrizione' </li>
            <li><label>Abbinamento: </label><input type='textarea' name='abbinamento' title='abbinamento' </li>
            <li><label>Degustazione: </label><input type='textarea'  name='degustazione' title='degustazione' </li>
            <li><input type='submit' name='save_profile' id='save_profile_modifications' value='Salva' /></li>
            </ul></fieldset>";


if(!empty($_POST['save_profile'])){
// controllo che tutti i campi siano non vuoti.
if(!empty($_POST['nome']) && !empty($_POST['tipologia']) && !empty($_POST['descrizione']) && !empty($_POST['denominazione']) && !empty($_POST['annata']) && !empty($_POST['vitigno']) && !empty($_POST['abbinamento']) && !empty($_POST['degustazione']) && !empty($_POST['gradazione']) && !empty($_POST['formato'])) {
    
        //dichiaro le variabili
    $nome = $_POST['nome'];
    $tipologia = $_POST['tipologia'];
    $descrizione = $_POST['descrizione'];
    $denominazione = $_POST['denominazione'];
    $annata = $_POST['annata'];
    $vitigno = $_POST['vitigno'];
    $abbinamento = $_POST['abbinamento'];
    $degustazione = $_POST['degustazione'];
    $gradazione = $_POST['gradazione'];
    $formato = $_POST['formato']);

    /* DA FINIRE
    ESEMPI DI CONTROLLI A SEGUIRE
        // controllo che l'anno sia del formato giusto

        if(is_numeric($anno) && strlen($anno)==4 && !preg_match("/^(\s)+$/",$anno)) {
            // controllo che l'anno non sia già presente all'interno del database

            $sql = "SELECT anno FROM annate WHERE anno='".$anno."'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) != 0){
                setcookie('error',"L'anno inserito esiste gi&agrave;"); 
                header("Location: add_wine.php");
            } else {
                // inserisco i dati nel database

                if(!isset($_POST['migliore']))
                {   
                    $sql = "INSERT INTO vini (nome, tipologia, descrizione, denominazione, annata, vitigno, abbinamento, degustazione, gradazione, formato, foto) VALUES ('".$anno."','".$descrizione."', '".$qualita."')";
                }
                else { 
                    $sql = "INSERT INTO annate (anno, descrizione,qualita,migliore) VALUES ('".$anno."','".$descrizione."', '".$qualita."', '1')";
                }
                //controllo la connessione
                if (mysqli_query($conn, $sql) == TRUE) {
                    setcookie('info',"Aggiunta avvenuta con successo.");
                    header("Location: admin_panel.php");
                } else {
                    setcookie('error',"Si &egrave; verificato un errore. La preghiamo di riprovare");
                    header("Location: add_wine.php");
                }
            }
        } else {
            setcookie('error','Anno non è nel formato giusto.');
            header("Location: add_wine.php"); 
        }
    */

    } else {
        setcookie('error','Alcuni campi risultano vuoti.');
        header("Location: add_wine.php"); 
    }
}
$vino.='</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina 

$pagina = str_replace("[SEARCH_WINE]", '', $pagina);

$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $vino, $pagina);
mysqli_close($conn);
?>
