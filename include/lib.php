<?php

//funzione che crea una password random
function random($lunghezza=12)
{
    $caratteri_disponibili ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $codice = "";
    for($i = 0; $i<$lunghezza; $i++){
        $codice = $codice.substr($caratteri_disponibili,rand(0,strlen($caratteri_disponibili)-1),1);
    }
    return $codice;
}

//converte le vocali accentate nelle rispettive entità HTML
function entityAccentedVowels($stringa)
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
    //non viene usata questa funzione perchè prima di tutto mysql fa il confronto anche tra lettere minuscole e maiuscole e viceversa e secondo con le lettere accentate dà problemi perchè altera la loro corretta codifica 
    //$input = strtolower($input);

    $preposizioni_articoli = array('è','é','e','ed','o','od','il','la','lo','i','gli','le','di','del','della','dello','dei','degli','delle','a','al','alla','allo','ai','agli','alle','da','dal','dalla','dallo','dai','dagli','dalle','in','nel','nella','nello','nei','negli','nelle','con','col','su','sul','sulla','sullo','sui','sugli','sulle','per','tra','fra','sotto','sopra');

    //tolgo tutte le preposizioni, gli articoli e le varie combinazioni dalla stringa di ricerca
    //uso un ciclo while perchè se in input ci sono più preposizioni/articoli di fila, non vengono tolti tutti in un singolo passaggio.  ES: ' a del lo il ' --> ' del il ' (1° passaggio) --> ' ' (2° passaggio)
    //Quindi finchè trovo corrispondenza con la mia espressione regolare, sostituisco. 
    while(preg_match('/\s('.implode('|',$preposizioni_articoli).')\s/',$input))
    $input = preg_replace('/\s('.implode('|',$preposizioni_articoli).')\s/',' ',$input);

    //tolgo i comandi di escape dalla stringa di ricerca
    $input = str_replace(array('\n','\r','\t'),' ',$input);
    
    //converto le vocali accentate nella stringa di ricerca nelle rispettive entità
    $input = entityAccentedVowels($input);
    
    //tolgo tutti i simboli di monete e cioè: € £ $
    $input = str_replace(array('€','£','$'),' ',$input);
    
    //tolgo tutta la punteggiatura dalla stringa di ricerca
    //[:punct:] è un array che contiene !"#$%&'()*+,\-./:;<=>?@[]^_`{|}~
    $input = preg_replace('/[[:punct:]][^&agrave;][^&eacute;][^&egrave;][^&igrave;][^&ograve;][^&ugrave;]/',' ',$input);

    //tolgo gli spazi, che si potrebbero essere creati con le funzioni precedenti, all'inizio e alla fine della stringa di ricerca
    $input = trim($input);

    //tolgo tutti gli spazi dalla stringa di ricerca e li sostituisco con un simbolo segnaposto
    $input = preg_replace('/\s+/','£',$input);

    //divido le parole della stringa di ricerca usando il simbolo segnaposto
    $input = explode('£',$input);

    return $input;
}

//NON SO DOVE VENGA UTILIZZATA
//toglie le parentesi triangolari per evitare injection code nei campi testuali dei form
function controlText($string)
{
    $string = str_replace(array('<','>'),' ',$string);
    return $string;
}

//funzione per fare l'escaping degli apici negli input testuali che sono sensibili ad injection code
function escapingText($string)
{
    //eventualmente c'è la funzione mysqli_real_escape_string($conn,$voce) da usare direttamente nel file .php
    
    $string = str_replace("'","\'",$string);
    return $string;
}


?>
