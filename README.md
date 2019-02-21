# WineNot
Lo scopo del progetto sviluppato è stato quello di implementare un sito web per un’enoteca, nella
quale un utente può visualizzare tutti i vini disponibili, le migliori annate, gli eventi e la storia
dell’enoteca. Il sito ha la funzione di vetrina per l’enoteca WineNot e serve per pubblicizzarla.
L’utente che visita il sito può accedere alle pagine Home, Vini, Vino specifico, Annate migliori,
Eventi, Storia e Contattaci.

# Configurazione database (./include/config.php)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "winenot";

# Database 
E' presente il file sql del database (./database/winenot.sql)

# Area amministrazione sito
Ci sono l’amministratore (unico) e i collaboratori. Entrambi i ruoli possono aggiungere/modificare/
eliminare vini e annate e anche modificare i propri dati profilo. Solo l’amministratore
potrà aggiungere/modificare/eliminare i collaboratori. 
Per accedere alla pagina di login è sufficiente scrivere "/admin" in coda all’url della pagina e premere invio.  
es. http://localhost/WineNot/index.html/admin

# Login admin 
username: admin
password: Admin420

# Login utente 
username: kevinsilvstr
password: Kevin940

Dalla pagina di login si può anche recuperare la propria password, utilizzando la mail associata all’account.
Accedendo è possibile visualizzare quattro sezioni: gestione vini, gestioni annate, gestione utenti
e dati profilo.



# Informazioni
Per altre informazioni consultare la relazione (./relazioneWineNot/relazioneWineNot.pdf)