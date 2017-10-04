<?php
function reindirizza($paginainterna=0) 
{
    $location='Location: ./index.php';
    if($paginainterna) $location.='?$paginainterna';
    header($location);
    exit;
}

//funzione che crea una password random
function random($lunghezza=12){
    $caratteri_disponibili ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $codice = "";
    for($i = 0; $i<$lunghezza; $i++){
        $codice = $codice.substr($caratteri_disponibili,rand(0,strlen($caratteri_disponibili)-1),1);
    }
    return $codice;
}

//fa la differenza tra date e ritorna true se la prima data e' minore/uguale alla seconda, false altrimenti
function datediff($prima_data, $seconda_data)
{
    $prima_data = strtotime($prima_data);
    $seconda_data = strtotime($seconda_data); 
    if ($prima_data <= $seconda_data) return true;
    else return false;
}

//funzione ritorna true se la persona è maggiorenne rispetto alla data attuale, false altrimenti
function Adult($data){
    $data_attuale=date("Y-m-d"); 
    $newdate = strtotime ( '-18 year' , strtotime ( $data_attuale ) ) ; // facciamo l'operazione
    $newdate = date ( 'Y-m-d' , $newdate ); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
    return datediff($data, $newdate);
}

function EntityAccentedVowels($stringa)
{
    $stringa = str_replace ('à', '&agrave;', $stringa);
    $stringa = str_replace ('é', '&eacute;', $stringa);
    $stringa = str_replace ('è', '&egrave;', $stringa);
    $stringa = str_replace ('ì', '&igrave;', $stringa);
    $stringa = str_replace ('ò', '&ograve;', $stringa);
    $stringa = str_replace ('ù', '&ugrave;', $stringa);
    return $stringa;
}

//pulisce l'input ricavando un array formato dalle parole chiavi e importanti
function cleanInput($input)
{
    $input = preg_replace ('/\sé+\s/', 'e', $input);
    $input = preg_replace ('/\sè+\s/', 'e', $input);

    $preposizioni_articoli = array('e','ed','o','od','il','la','lo','i','gli','le','di','del','della','dello','dei','degli','delle','a','al','alla','allo','ai','agli','alle','da','dal','dalla','dallo','dai','dagli','dalle','in','nel','nella','nello','nei','negli','nelle','con','col','su','sul','sulla','sullo','sui','sugli','sulle','per','tra','fra','sotto','sopra');

    //tolgo tutte le preposizioni, gli articoli e le varie combinazioni dalla stringa di ricerca
    $input = preg_replace('/\s('.implode('|',$preposizioni_articoli).')\s/',' ',$input);

    //tolgo i comandi "\qualcosa" dalla stringa di ricerca    Es. \n
    $input = str_replace(array('\n','\r'),' ',$input);

    //tolgo tutta la punteggiatura dalla stringa di ricerca
    $input = preg_replace(array('/\s[[:punct:]]+\s/'),' ',$input);
    $input = preg_replace(array('/[[:punct:]]+\s/'),' ',$input);
    $input = preg_replace(array('/\s[[:punct:]]+/'),' ',$input);

    //tolgo gli accenti dalle vocali nella stringa di ricerca
    $input = EntityAccentedVowels($input);

    //tolgo gli spazi, che si potrebbero essere creati con le funzioni precedenti, all'inizio e alla fine della stringa di ricerca
    $input = trim($input);

    //tolgo tutti gli spazi dalla stringa di ricerca e li sostituisco con un simbolo segnaposto
    $input = preg_replace('/\s+/','£',$input);

    //divido le parole della stringa di ricerca usando il simbolo segnaposto
    $input = explode('£',$input);

    return $input;
}


//ritorna un array in cui ogni elemento è un intervallo di date divise dal simbolo '£'
//questi intervalli di date sono stati creati confrontando le date dell'annuncio e le date degli alloggi relativi
function availableDates($annuncio, $conn){
    $sql = "SELECT data_inizio, data_fine FROM annunci WHERE cod_annuncio='".$annuncio."'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $dataInizio=$row['data_inizio'];
    $dataFine=$row['data_fine'];

    $sql = "SELECT alloggi.data_inizio, alloggi.data_fine FROM alloggi INNER JOIN annunci ON annuncio=cod_annuncio WHERE cod_annuncio='".$annuncio."' AND (stato=1 OR stato=3) ORDER BY alloggi.data_inizio";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)>0){
        $aux=array();
        $i=0;
        $continua=0;
        $data_A=$dataInizio;

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $data_B=$row['data_inizio'];
            if(!empty($continua)) {
                if($aux[$i]!=$data_B){
                    $aux[$i].='£'.$data_B;
                    $i++;
                }
                else unset($aux[$i]); 
                $continua=0;
                $data_A=$row['data_inizio'];
            }
            if(!datediff($data_B, $data_A)){
                $aux[$i]=$data_A.'£'.$data_B;
                $i++;
                $data_A=$row['data_fine'];
                $data_B=$dataFine;
            }
            else if(!datediff($dataFine,$row['data_fine'])){
                $aux[$i]=$row['data_fine'];
                $continua=1;
            }
        }
        if(!empty($continua)) $aux[$i].='£'.$dataFine;       
        if(!datediff($data_B, $data_A)) $aux[$i]=$data_A.'£'.$data_B;  
    }
    else $aux=array($dataInizio.'£'.$dataFine);


    return $aux;
}

//nella barra orizzontale in alto a destra vicino a notifiche fa comparire il numero di notifiche/conversazioni aggiornate, nuove o con nuovi messaggi
function notify($session, $conn){
    //NOTIFICA IL MUNERO DI CONVERSAZIONI CON MESSAGGI NUOVI
    //query per avere tutte le conversazioni che riguardano l'utente
    $sql = "SELECT * FROM conversazioni WHERE mittente='".$session."' OR destinatario='".$session."'";
    $result = mysqli_query($conn,$sql);
    $cont=0;
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        //controllo per mostrare all'utente se è presente un nuovo messaggio non letto
        if(($session==$row['mittente'] && $row['ultima_risposta']==1 && $row['da_leggere']==1)||($session==$row['destinatario'] && $row['ultima_risposta']==0 && $row['da_leggere']==1)){
            $cont ++;
        }
    }
    $sql = "SELECT alloggi.* FROM alloggi INNER JOIN annunci ON annuncio=cod_annuncio INNER JOIN immobili ON annunci.immobile=cod_immobile WHERE proprietario='".$session."' OR locatario='".$session."'"; 
    $result = mysqli_query($conn,$sql); 
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ 
        //controllo per mostrare all'utente se è presente una nuova notifica non vista 
        if($row['notifica']==1) $cont ++; 
    } 
    if($cont>0) $aux= "<span id='message_notification'>".$cont."</span>";
    else $aux= "";

    return $aux;
}

//quando il proprietario accetta una richiesta, tutte le altre richieste dello stesso annuncio con date che si sovrappongono vengono rifiutate
function checkOtherLodging($cod_alloggio, $conn){
    $sql = "SELECT * FROM alloggi WHERE cod_alloggio='".$cod_alloggio."'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $data_inizio=$row['data_inizio'];
    $data_fine=$row['data_fine'];

    $sql = "SELECT * FROM alloggi WHERE annuncio='".$row['annuncio']."' AND stato=0";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        if(($data_inizio<=$row['data_inizio'] && $row['data_inizio']<$data_fine) || ($data_inizio<$row['data_fine'] && $row['data_fine']<=$data_fine)){
            //aggiorno stato=2
            $sql2="UPDATE alloggi SET stato=2 WHERE cod_alloggio='".$row['cod_alloggio']."'";
            $result2 = mysqli_query($conn,$sql2);
            //controllo la connessione
            if (!$result2) {
                $lista.="errore di connessione";
            }
        }
    }
}

//ritorna true se la data odierna è maggiore della data di fine alloggio
function dateControl($cod_alloggio, $conn){
    $sql = "SELECT data_fine FROM alloggi WHERE cod_alloggio='".$cod_alloggio."'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if(!datediff(date("Y-m-d"),$row['data_fine'])) return true;
    else return false;
}

//funzione per il controllo degli alloggi o richieste pendenti
function checkPending($cod_annuncio, $conn){
    $sql = "SELECT * FROM alloggi INNER JOIN annunci ON annuncio=cod_annuncio WHERE cod_annuncio='".$cod_annuncio."' AND (stato=1 OR stato=0)";

    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) == 0) return true;
    else return false;
}

//quando il proprietario cerca di inserire un annuncio controllo che non ci siano annunci con date che si sovrappongono per lo stesso immobile. Ritorna true se rileva date coincidenti
function checkDateAds($cod_immobile, $data_inizio, $data_fine, $conn){
    $temp=false;

    $sql = "SELECT data_inizio, data_fine FROM annunci INNER JOIN immobili ON immobile=cod_immobile WHERE cod_immobile='".$cod_immobile."'";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            if(($data_inizio<=$row['data_inizio'] && $row['data_inizio']<$data_fine) || ($data_inizio<$row['data_fine'] && $row['data_fine']<=$data_fine)){
                $temp=true;
            }
        }
    }

    return $temp;
}

//toglie le parentesi triangolari per evitare injection code nei campi testuali dei form
function controlText($string){
    $string = str_replace(array('<','>'),' ',$string);
    return $string;
                 }

//aggiorna il numero dei letti in immobile
function refreshNumberBeds($cod_immobile,$sommaletti,$conn){
    $sql="SELECT numero_letti FROM immobili WHERE cod_immobile='".$cod_immobile."'";
    $result=mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if($row["numero_letti"]!=$sommaletti){
        $sql="UPDATE immobili SET numero_letti='".$sommaletti."' WHERE cod_immobile='".$cod_immobile."'";
        $result = mysqli_query($conn,$sql);
    }
}
?>
