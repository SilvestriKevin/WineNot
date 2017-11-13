<?php 
//apro la sessione
session_start(); 

//inclusione file di connessione
include_once("../include/config.php");

//inclusione file per funzioni ausiliarie
include_once("../include/lib.php");

if(!isset($_SESSION['id'])) header("Location: ../index.php");

$vini='';
$info_errore='';

if(!empty($_COOKIE['info'])){
    $info_errore.="<li>".$_COOKIE['info']."</li>";
    setcookie('info',null);
}
if(!empty($_COOKIE['error'])){
    $info_errore.="<li>".$_COOKIE['error']."</li>";
    setcookie('error',null);
}

if(isset($_GET['section'])) $section = $_GET['section'];
else if(isset($_POST['section'])) $section = $_POST['section'];

if(isset($section)){
    switch($section){
        case 'garbage':
            if(isset($_POST['delete_finally_selected'])){

                $wines = isset($_POST['wines']) ? $_POST['wines'] : array();
                if (!count($wines)) {
                    setcookie('error',"Selezionare almeno un elemento");
                    header("Location: admin_panel.php?section=garbage");
                }   
                else{
                    $num_elem = count($wines);
                    $sql="DELETE vini WHERE id_wine ='";
                    for($i=0 ; $i<$num_elem ; $i++){
                        if($i!=0) $sql.="' or '";
                        $sql.=$wines[$i];
                    }
                    $sql.="'";
                    $result = mysqli_query($conn,$sql);
                    //controllo la connessione
                    if ($result) {
                        setcookie('info',"Elementi eliminati definitivamente");
                        header("Location: admin_panel.php?section=garbage");
                    }
                    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
                    header("Location: admin_panel.php?section=garbage");
                }
            }

            if(isset($_POST['restore_selected'])){

                $wines = isset($_POST['wines']) ? $_POST['wines'] : array();
                if (!count($wines)) {
                    setcookie('error',"Selezionare almeno un elemento");
                    header("Location: admin_panel.php?section=garbage");
                }   
                else{
                    $num_elem = count($wines);
                    $sql="UPDATE vini SET cestino = 0 WHERE id_wine ='";
                    for($i=0 ; $i<$num_elem ; $i++){
                        if($i!=0) $sql.="' or id_wine = '";
                        $sql.=$wines[$i];
                    }
                    $sql.="'";
                    $result = mysqli_query($conn,$sql);
                    //controllo la connessione
                    if ($result) {
                        setcookie('info',"Elementi ripristinati");
                        header("Location: admin_panel.php?section=garbage");
                    }
                    else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
                    header("Location: admin_panel.php?section=garbage");
                }
            }
            
            $vini.='<input type="hidden" name="section" value="garbage" />';

            $vini.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
            $vini.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
            $vini.='<input type="submit" name="restore_selected" id="restore_selected" value="Ripristina Selezionati" />';
            $vini.='<input type="submit" name="delete_finally_selected" id="delete_finally_selected" value="Elimina Selezionati" /></div>';

            //STAMPA I VINI (PRESENTI NEL CESTINO)
            $sql = "SELECT vini.* FROM vini WHERE cestino=1";
            $result=mysqli_query($conn,$sql);

            $vini.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Denominazione</div>
                            <div class="wines_td">Tipologia</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td modify_column">Elimina</div>

                    </div>';

            if(mysqli_num_rows($result)!=0)
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $vini.="<div class='wines_tr'>";
                    $vini.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['id_wine'];
                    if(isset($_POST['all_selected'])) $vini.="' checked='checked";
                    $vini.="'></div>";
                    $vini.="<div class ='wines_td'>".$row['denominazione']."</div>";
                    $vini.="<div class ='wines_td'>".$row['tipologia']."</div>";
                    $vini.="<div class ='wines_td'>".$row['annata']."</div>";
                    $vini.="<div class ='wines_td remove_column'><a title='Elimina definitivamente vino' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></div>";
                    $vini.="</div>";
                }
            else $vini.="<h2>Non sono presenti vini.</h2>";           
            break;            
    }
}
else {
    if(isset($_POST['delete_selected'])){

        $wines = isset($_POST['wines']) ? $_POST['wines'] : array();
        if (!count($wines)) {
            setcookie('error',"Selezionare almeno un elemento");
            header("Location: admin_panel.php");
        }   
        else{
            $num_elem = count($wines);
            $sql="UPDATE vini SET cestino = 1 WHERE id_wine = '";
            for($i=0 ; $i<$num_elem ; $i++){
                if($i!=0) $sql.="' OR id_wine = '";
                $sql.=$wines[$i];
            }
            $sql.="'";
            $result = mysqli_query($conn,$sql);
            //controllo la connessione
            if ($result) {
                setcookie('info',"Elementi spostati nel cestino");
                header("Location: admin_panel.php");
            }
            else setcookie('error',"Si è verificato un errore. La preghiamo di riprovare");
            header("Location: admin_panel.php");
        }
    }

    $vini.='<div><input type="submit" name="all_selected" id="all_selected" value="Seleziona Tutti" />';
    $vini.='<input type="submit" name="none_selected" id="none_selected" value="Deseleziona Tutti" />';
    $vini.='<input type="submit" name="delete_selected" id="delete_selected" value="Cestina Selezionati" /></div>';

    //STAMPA I VINI (QUANDO SI APRE LA PAGINA LA PRIMA VOLTA)
    $sql = "SELECT vini.* FROM vini WHERE cestino=0";
    $result=mysqli_query($conn,$sql);

    $vini.='<div class="wines_tr" id="wines_header">
                            <div class="wines_td">Selezione</div>
                            <div class="wines_td">Denominazione</div>
                            <div class="wines_td">Tipologia</div>
                            <div class="wines_td">Annata</div>
                            <div class="wines_td modify_column">Modifica</div>
                            <div class="wines_td remove_column">Cestina</div>
                    </div>';

    if(mysqli_num_rows($result)!=0)
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $vini.="<div class='wines_tr'>";
            $vini.="<div class ='wines_td'><input type='checkbox' name='wines[]' value='".$row['id_wine'];
            if(isset($_POST['all_selected'])) $vini.="' checked='checked";
            $vini.="'></div>";
            $vini.="<div class ='wines_td'>".$row['denominazione']."</div>";
            $vini.="<div class ='wines_td'>".$row['tipologia']."</div>";
            $vini.="<div class ='wines_td'>".$row['annata']."</div>";
            $vini.="<div class ='wines_td modify_column'><a title='Modifica vino' class='' href='./modify_wine.php' tabindex='' accesskey=''>Modifica</a></div>";
            $vini.="<div class ='wines_td remove_column'><a title='Elimina vino' class='' href='./delete_wine.php' tabindex='' accesskey=''>X</a></div>";
            $vini.="</div>";
        }
    else $vini.="<h2>Non sono presenti vini.</h2>";
}

//creazione della pagina web
//leggo il file e lo inserisco in una stringa
$pagina = file_get_contents("../html/admin_panel.html");
//rimpiazzo il segnaposto con la lista di articoli e stampo in output la pagina  
$pagina = str_replace("[INFO/ERRORE]", $info_errore, $pagina);
echo str_replace("[VINI]", $vini, $pagina);
mysqli_close($conn);
?>
