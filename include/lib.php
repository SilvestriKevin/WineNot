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

//toglie le parentesi triangolari per evitare injection code nei campi testuali dei form
function controlText($string)
{
    $string = str_replace(array('<','>'),' ',$string);
    return $string;
}

//funzione per fare l'escaping degli apici negli input testuali che sono sensibili ad injection code
function escapingText($string)
{
    $string = str_replace("'","\'",$string);
    return $string;
}


?>
