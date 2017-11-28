<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

$annata='';
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

$annata.='<form action="add_year.php" method="post">';        
$annata.= '<fieldset id="register_fieldset">
                    <ul>
                    <li><span>Tutti i campi sono obbligatori</span></li>
                    <li><label>Anno:</label>
                    <input type="text" maxlength="4" name="anno" title="anno" tabindex="1"/>
                    <li><label>Descrizione:</label>
                    <input type="textarea" maxlength="50" name="descrizione" title="descrizione" tabindex="1"/>
                        </li>
                        <li><label>Qualit&agrave;:</label>
                    <input type="text" maxlength="100" name="qualita" title="qualita" tabindex="6"/>
                        </li>
                        <li><label>Migliore: </label><input type="checkbox" name="migliore" title="migliore" value="1" tabindex="7"/></li>
                        <li><input type="submit" class="search_button" name="salva" value="Aggiungi" accesskey="s" tabindex="8"/></li>
                    </ul>

                </fieldset>';

if(isset($_POST['anno']) && isset($_POST['descrizione']) && isset($_POST['qualita'])) {
    // controllo che non siano stati lasciati spazi vuoti all'interno di anno e che tutti i campi siano non vuoti.

    if(!empty($_POST['anno']) && !empty($_POST['descrizione']) && !empty($_POST['qualita'])) {
        //dichiaro le variabili

        $anno = $_POST['anno'];
        $descrizione = $_POST['descrizione'];
        $qualita = $_POST['qualita'];

        // controllo che l'anno sia del formato giusto

        if(is_numeric($anno) && strlen($anno)==4 && !preg_match("/^(\s)+$/",$anno)) {
            // controllo che l'anno non sia già presente all'interno del database

            $sql = "SELECT anno FROM annate WHERE anno='".$anno."'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) != 0){
                setcookie('error',"L'anno inserito esiste gi&agrave;"); 
                header("Location: add_year.php");
            } else {
                // inserisco i dati nel database
                
                if(!isset($_POST['migliore']))
                {   
                    $sql = "INSERT INTO annate (anno, descrizione,qualita) VALUES ('".$anno."','".$descrizione."', '".$qualita."')";
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
                    header("Location: add_year.php");
                }
            }
        } else {
            setcookie('error','Anno non è nel formato giusto.');
            header("Location: add_year.php"); 
        }

    } else {
        setcookie('error','Alcuni campi risultano vuoti.');
        header("Location: add_year.php"); 
    }
}
$annata.='</form>';

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina 

$pagina = str_replace("[SEARCH_WINE]", '', $pagina);

$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[DATI]", $annata, $pagina);
mysqli_close($conn);
?>
