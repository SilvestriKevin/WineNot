-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 10, 2018 alle 15:48
-- Versione del server: 5.7.17
-- Versione PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `winenot`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `annate`
--

CREATE TABLE `annate` (
  `anno` year(4) NOT NULL,
  `descrizione` text NOT NULL,
  `qualita` varchar(20) NOT NULL,
  `migliore` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `annate`
--

INSERT INTO `annate` (`anno`, `descrizione`, `qualita`, `migliore`) VALUES
(2007, 'L&apos;andamento climatico bizzarro ha portato, tra alti e bassi, a una qualit&agrave; eterogenea ma complessivamente assai interessante per le variet&agrave; precoci. Per le tipologie vendemmiate dopo la met&agrave; di settembre i livelli sono risultati ottimi, con diverse punte di eccellente. I rossi hanno raggiunto i massimi livelli, con eccellenti profumi e una esuberante carica di tannini morbidi dovuti all&apos;ottimale maturit&agrave; fenolica.', 'ottimo', 1),
(2009, 'La qualit&agrave; ha maggiormente premiato il Centro-Nord d&apos;Italia, dove, in molte regioni, &egrave; stata eccellente. Nel Centro-Sud il bizzarro andamento climatico e meteorico, caratterizzato prima da temperature elevate, poi da piogge di durata inconsueta, ha mantenuto l&apos;eterogeneit&agrave; inizialmente ipotizzata determinando una qualit&agrave; a macchia di leopardo.', 'ottimo', 0),
(2010, 'L&apos;eterogeneit&agrave; qualitativa di fine agosto &egrave; stata confermata a fine campagna, con un&apos;Italia vinicola mista, dove in una stessa regione il buono si &egrave; scontrato con l&apos;eccellente e l&apos;ottimo con il mediocre. Complessivamente la qualit&agrave; della produzione 2010 &egrave; risultata buona ma con assenza di eccellenze.', 'buona', 1),
(2012, 'Settembre con sole, qualche pioggia e una buona escursione termica notturna, condizioni che hanno siglato per la stragrande maggioranza delle regioni un&apos;ottima annata. Per i vini bianchi &egrave; stata tra le migliori degli ultimi anni e, per alcuni rossi, molto vicina a quella del 1997.', 'ottima', 0),
(2013, 'Le premesse per firmare un ottimo millesimo, nonostante le bizzarrie del tempo, c&apos;erano tutte fino a Ferragosto. Ma le piogge e le basse temperature che hanno caratterizzato la seconda met&agrave; del mese hanno rimesso per&ograve; tutto in discussione. Dunque una qualit&agrave; buona con poche punte qualificate al nord e di maggiore interesse al Sud e nelle Isole.', 'buona', 1),
(2016, 'Nel Centro-Nord la vendemmia sar&agrave; ricordata come la migliore degli ultimi cinque anni: complessivamente ottima, anche se con poche punte di eccellenza. Maggiormente eterogenea al Sud e nelle Isole dove si &egrave; riscontrata una pi&ugrave; accentuata variabilit&agrave; dovuta alle bizzarrie del tempo.', 'ottima', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_user` int(3) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_user`, `nome`, `username`, `password`, `email`, `admin`) VALUES
(1, 'admin', 'admin', '8b31f7222e545bd99cae22e91c551f5a', 'admin@gmail.com', 1),
(5, 'Kevin Silvestri', 'kevinsilvstr', '662e57ad7ba1f1de34f9ec978de8dd35', 'kevinsilvestri94@gmail.com', 0),
(6, 'Cristian Pirlog', 'pirlott93', '1ed4e15ac27115816f3043927a082fa8', 'pirlogcristian93@gmail.com', 0),
(7, 'Eleonora Thiella', 'eleonorath', '2ce0b8821f7fb76b1fa90ce3be691e2a', 'thiellaele@gmail.com', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `vini`
--

CREATE TABLE `vini` (
  `id_wine` smallint(3) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tipologia` varchar(255) NOT NULL,
  `descrizione` text NOT NULL,
  `denominazione` varchar(255) NOT NULL,
  `annata` year(4) NOT NULL,
  `vitigno` varchar(255) NOT NULL,
  `abbinamento` text NOT NULL,
  `degustazione` text NOT NULL,
  `gradazione` decimal(3,1) NOT NULL,
  `formato` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `vini`
--

INSERT INTO `vini` (`id_wine`, `nome`, `tipologia`, `descrizione`, `denominazione`, `annata`, `vitigno`, `abbinamento`, `degustazione`, `gradazione`, `formato`) VALUES
(1, 'Chardonnay', 'Bianco', ' A partire dalla ricchezza del colore dell&apos;oro chiaro profondo ma trasparente si intuisce il piacevole contrasto di cremosit&agrave; e croccantezza che ci aspetter&agrave; all&apos;assaggio. &Egrave; infatti un bianco morbido sorretto da una rinfrescante vena acida che accompagna tutto l&apos;assaggio. Un sottile tratto minerale fa dialogare con armonia il sapore delle pesche gialle mature, del miele d&apos;acacia e della pasta di mandorla con i profumi pi&ugrave; torbati, cerealicoli e tostati che si affacciano sul finale potente ed equilibrato.', 'Sicilia Menfi D.O.C.', 2007, '100% Chardonnay', 'Da provare con un po&apos; di foie gras appena scottato o con arrosto di vitello e salsa di funghi', 'Perfetto per una cena romantica o una serata importante', '14.0', '1.50'),
(2, 'Cometa', 'Bianco', 'Fiano in purezza proveniente da terreni calcareo-argillosi che contribuiscono ad esaltare i marcatori aromatici di macchia mediterranea propri di questo vitigno. Cos&igrave;, tra il fiore di ginestra ed il timo, si possono rintracciare dei delicati profumi di camomilla, fieno e mandarino. Palato minerale con tracce di sapidit&agrave; vibranti che scuotono e dissetano. Poi il ritmo cambia per diventare pi&ugrave; lento ed armonico, un invito a sorseggiare questo bianco d&apos;autore con la giusta calma godendosi appieno ogni sfumatura. Il finale regala una sensazione tattile setosa con un retrogusto raffinatissimo di albicocche mature, di mandorli in fiore e foglia di basilico.', 'Sicilia Menfi D.O.C.', 2009, '100% Fiano', 'Senza paura di essere banali, con alcuni pesci freschi appena pescati e dentice alla griglia', 'Ideale per un pranzo tra amici o colleghi', '14.0', '0.75'),
(3, 'Burdese', 'Rosso', 'Rosso potente, costruito su una trama tannica fitta ed importante bilanciata da una alcolicit&agrave; decisa. Forza gustativa incentrata su un fruttato maturo molto denso e ricco di polpa. I sapori sono quelli del mirtillo e delle amarene sotto spirito che si mescolano al cuoio mentre gradatamente affiorano dei profumi intensi di ciliegie, cacao, iodio e rosmarino.', 'Sicilia DOC', 2009, 'Cabernet Sauvignon 70%, Cabernet Franc 30%', 'Vino grintoso, deciso ed energico da godersi da solo o con abbinamenti di altrettanta forza e carattere e dunque degli stufati, o della selvaggina sulle carni. Per un&apos;alternativa vegetariana provate del radicchio grigliato con del formaggio stagionato o delle cipolle arrostite, quasi caramellizzate', 'Perfetto per un pranzo in compagnia', '14.0', '1.00'),
(4, 'Sito dell&apos;Ulmo Merlot', 'Rosso', '&Egrave; un rosso sfaccettato e intrigante che unisce alla piacevolezza del tannino una straordinaria lunghezza gustativa. Quest&apos;idea di Merlot dal colore rubino compatto, ricchissimo di succo dolce di ribes nero e di viola candita che si alternano al timo e la lavanda non pu&ograve; passare inosservato sia al palato di un raffinato degustatore che a quello di un appassionato alle prime armi. Un vino che ti ammalia e ti conquista dolcemente senza lasciare ricordi spiacevoli. Le sue singolarit&agrave; emergono in particolar modo nell&apos;architettura poliedrica eppure agilissima dei suoi tannini e nella forza gustativa sapida ed intensa.', 'Sicilia DOC', 2010, '100% Merlot', ' Sebbene lo si beva con estrema facilit&agrave; da solo, la sua eleganza lo rende adatto a carni bianche cucinate anche con salse impegnative. Oppure, a pesce delicato cucinato senza limone, magari accompagnato da verdure arrosto', 'Perfetto per un aperitivo o un pranzo', '14.0', '1.50'),
(5, 'Maroccoli Syrah', 'Rosso', ' Spezie dolci orientali ed un lieve ricordo di cacao tostato accompagnano il vino in tutte le fasi degustative, adesso aprendo uno spazio al frutto nero nettissimo e solare, adesso a note pi&ugrave; invernali di cuoio e humus. Tocco avvolgente\r\nche ricorda la consistenza del velluto con un finale di mentuccia e pepe nero.', 'Sicilia DOC', 2016, '100% Syrah', 'Costolette d&apos;agnello marinate e grigliate, accompagnate con della mentuccia o anche con una parmigiana ortodossa con della provola affumicata.', 'Ideale per un pranzo in famiglia', '14.0', '0.75'),
(6, 'Alastro', 'Bianco', 'Finemente aromatico, delicatamente strutturato, con profumi di frutti tropicali, agrumi\r\nfreschi e fiori bianchi.  Il colore giallo paglierino trasparente fa presagire un vino lieve e scorrevole. Ma l&apos;abito in questo caso &egrave; una mimetica che il Grecanico mette per nascondere la forza ed il carattere poliedrico di questo bianco. Il matrimonio con Grillo e Sauvignon\r\nda un naso ricco di profumi di cedro, pesca, frutti tropicali e fiori gialli, come la ginestra. In bocca &egrave; vibrante, ma cremoso e setoso con note di melone bianco.', 'Sicilia DOC', 2016, '70% Grecanico, 15% Grillo e 15% Sauvignon Blanc', ' Il contrasto tra sapidit&agrave; e dolcezza gli\r\nconferisce una forza gastronomica che lo rende adatto anche agli abbinamenti con piatti semplici come un&apos;insalata di mare tiepida che a piatti pi&ugrave; complessi come un piatto asiatico speziato in salsa agrodolce. Si abbina con i primi piatti della cucina mediterranea e con la cucina vegetariana', 'Perfetto per un aperitivo o un pranzo', '13.0', '1.00'),
(7, 'Plumbago', 'Rosso', ' Viola porpora saturo che non vedi l&apos;ora di berlo. Plumbago, dominato dal profumo delle prugne mature e delle more selvatiche, mostra dei tratti aromatici piacevolmente complessi di incenso, gelsomino e tartufo nero. La tessitura del tannino &egrave; variegata ed unisce degli aspetti morbidi, dolci e seducenti, come una torta Sacher, a quelli pi&ugrave; duri, diretti ed austeri di una tavoletta di cioccolato modicano.', 'Sicilia DOC', 2007, '100% Nero d&apos;Avola', 'Perfetto negli abbinamenti con carni alla brace con cui ritrova affinit&agrave; sui toni affumicati. La sua naturale dolcezza si sposa con eventuali salse', 'Ideale per un pranzo in famiglia', '14.0', '0.75'),
(8, 'Terebinto', 'Bianco', ' Variet&agrave; siciliana spiccatamente aromatica, con profumi di albicocca, litchies e frutta tropicale. Colore giallo chiaro con riflessi verdi: fresco e vibrante nonostante una discreta presenza alcolica.', 'Sicilia DOC', 2010, '100% Grillo', ' Crudit&agrave; di mare, pasta con i ricci, fritture di verdure e di pesce. Bene anche con formaggi a pasta bianca e con verdure in pinzimonio', 'Perfetto per un aperitivo o un pranzo', '14.0', '1.00'),
(9, 'La Segreta Il Bianco', 'Bianco', 'Questo vino giovane e fresco ottenuto principalmente da uve Grecanico guadagna personalit&agrave; e stile con l&apos;aggiunta delle uve internazionali. Colore giallo chiaro con riflessi verdi che ne anticipano la freschezza. Al naso note giovanili agrumate e floreali, bilanciate da vive note mediterranee, pesca, papaya e camomilla. Equilibrato e fine al palato, grazie ad una ben dosata acidit&agrave;.', 'Sicilia DOC', 2016, '50% Grecanico, 30% Chardonnay, 10% Viognier, 10% Fiano', 'Ottimo per un fresco aperitivo e molto versatile negli abbinamenti di vari antipasti, insalate e primi piatti a base di sughi leggeri o di mare.', 'La Segreta &egrave; compagno versatile del bere quotidiano e adatto alle pi&ugrave; diverse occasioni', '13.0', '1.50'),
(10, 'La Segreta Il Rosso', 'Rosso', ' Colore rosso rubino intenso e vivido. Al naso profumi di ribes e gelso con un tenue finale speziato e mentolato. In bocca il vino mostra la sua bella personalit&agrave; con sapori di frutti di bosco maturi, bilanciati da un gusto pieno ed erbaceo in modo assolutamente piacevole. I tannini sono docili e ben levigati conferendo cos&igrave; a questo rosso un carattere molto versatile.', 'Sicilia DOC', 2007, '50% Nero d&apos;Avola, 25% Merlot, 20% Syrah, 5% Cabernet Franc', 'Per un consumo quotidiano &egrave; ideale nell&apos;abbinamento di primi mediterranei, piatti leggeri di carni, verdure o anche del pesce azzurro', 'La Segreta &egrave; compagno versatile del bere quotidiano e adatto alle pi&ugrave; diverse occasioni', '13.0', '0.75'),
(11, 'Ros&eacute;', 'Ros&eacute;', ' Il colore delicato del Ros&eacute; ci fa ricordare l&apos;eleganza di una prima ballerina. Note floreali come quelle dell&apos;ibisco incontrano sentori di fragoline, lamponi e rabarbaro. Vivace e fresco in bocca ricorda la meringa e richiama la polpa chiara delle pesche\r\ntabacchiere.', 'Sicilia DOC', 2016, '50% Nero d&apos;Avola, 50% Syrah', 'Perfetto da abbinarsi con qualunque idea di snack vi possa passare per la testa durante i vostri pomeriggi estivi. Bene anche con frutti di mare e fritture vegetali, tempura o sushi', 'Ideale per brindisi e occasioni di festeggiamento', '12.0', '0.75'),
(12, 'Dorilli', 'Rosso', 'Sembra quasi che il vino prenda il suo colore rubino-violaceo dalle sabbi rosse di questi vite di Nero d&apos;Avola e Frappato. I profumi di ciliegia nera matura si confondo con quello del cardamomo, della vaniglia e della cannella. Il tannino - maturo, morbido, rotondo- si scioglie in bocca grazie ad un&apos;incredibile dolcezza di frutto. Qui, le spezie orientali si mescolano a dei sapori complessi di pancetta affumicata, mosto cotto, maggiorana e fichi d&apos;india.', 'Cerasuolo di Vittoria Classico DOCG', 2012, '70% Nero d&apos;Avola, 30% Frappato', 'Straordinario con arrosto di maiale ben pepato. La sua complessit&agrave; salutare si adatta bene a pesce grasso brasato cotto in liquido e si pu&ograve; anche abbinare bene con del pesce piccante cucinato in uno stile asiatico', 'Perfetto per una cena romantica o una serata importante', '13.0', '1.00'),
(13, 'Cerasuolo di Vittoria', 'Rosso', 'Vino intrigante con una carica di energia vitale straordinaria a base di frutti di bosco, fragoline, gelso e melograno. Una versione di Cerasuolo estremamente gastronomica che ci piace molto anche per le sue note carnose e pepate. In bocca,\r\nil vino rispecchia le percezioni olfattive e ritroviamo felicemente il pepe nero che si mescola alla carruba e alle amarene dolci. Palato scattante con un finale molto sapido e tondo con note di gelso.', 'Cerasuolo di Vittoria DOCG', 2012, '60% Nero d&apos;Avola, 40% Frappato', ' In stagione, perfetto con un trancio di tonno appena scottato, con dei funghi trifolati o della cacciagione da piuma, la sua armonia con pizze invece sorprende tutto l&apos;anno', 'Ideale per un pranzo in famiglia', '13.0', '1.00'),
(14, 'Frappato', 'Rosso', 'Rara ed esclusiva variet&agrave; coltivata in poche decine di ettari, si esprime al meglio nelle sabbie\r\nrosse di Vittoria, non lontana dal mare. Un rosso aromatico e piacevole come pochi, perfetta sintesi tra tradizione e tendenza. Tipiche le note di rosa e viola candita con un tono elegantemente affumicato. In bocca tanta frutta rossa con toni balsamici.', 'Vittoria DOC', 2016, '100% Frappato', 'La straordinaria versatilit&agrave; di questo Claret siciliano si libera su intramontabili del quotidiano, come rigatoni all&apos;amatriciana, spaghetti alla bolognese e lasagne. La sua indole duttile sconfina nel contemporaneo, ideale su cheeseburger. Ipnotico su formaggi freschi e morbidi, in abito da sera con una zuppa di funghi e cipolle', 'Versatile nel bere quotidiano e adatto alle pi&ugrave; diverse occasioni', '13.0', '0.75'),
(15, 'Santa Cecilia', 'Rosso', 'Un vino speziatissimo e fruttato, brillante e limpido, profumato di carrubo, di bergamotto e scorza d&apos;arancio. Il frutto maturo e compatto e le note balsamiche al palato si sciolgono in modo dolce e vigoroso nell&apos;accompagnare un tannino\r\ndi trama fittissima ma aperta e calibrata alla struttura di questo vino dalle note profonde e baritonali. Le tracce di grafite gli conferiscono dei lineamenti di alto lignaggio gustativo mentre la mora selvatica e la ciliegia estratte sapientemente tengono il vino su un registro di grande rigorosit&agrave; stilistica e degustativa.', 'Noto DOC', 2009, '100% Nero d&apos;Avola', 'Un vino che non teme gli abbinamenti\r\npi&ugrave; rischiosi come della carne di maiale marinate anche con del peperoncino o dei piatti di pesce dalla carne ricca e fibrosa', 'Ideale per un pranzo tra amici o colleghi', '14.0', '0.75'),
(16, 'Moscato Bianco', 'Bianco', ' Giallo molto chiaro con riflessi verdi. Gelsomino, petali di rosa, carcad&egrave;, pompelmo rosa e profumi di mare: un&apos;esplosione poetica. In bocca sapido e fresco, equilibrato ed elegante.', 'Noto DOC', 2007, '100% Moscato Bianco', 'La sua fragrante aromaticit&agrave; si esalta sul contrasto morbido-sapido del mare: voluttuoso su un&apos;insalata di aringhe, gentile su un&apos;insalata caprese, &egrave; straordinario su gamberi e crostacei panati o su una crema di pomodoro e astice. Solare e conviviale, valorizza con precisione millimetrica una paella valenciana, un cous cous di pesce o dei tagliolini ai ricci di mare', 'Perfetto per un aperitivo in compagnia o una cena elegante', '14.0', '1.00'),
(17, 'Passito di Noto', 'Bianco', 'L&apos;appassimento di circa un mese e mezzo garantisce un&apos;alta concentrazione di zuccheri ed alcune naturali trasformazioni dell&apos;uva contribuiscono a dare straordinarie profumazioni di albicocche, petali di rosa, papaya e mela cotogna. Bocca dolce e carnosa, ma vivace con note agrumate. Rimanda al torrone e allo zenzero candito.', 'Noto DOC', 2016, '100% Moscato Bianco', 'Vitigno nato per abbinarsi ai migliori dolci, nella sua veste passita predilige quelli secchi possibilmente arricchiti da qualche frutto con una nota acida prominente', 'Ideale per brindisi e occasioni di festeggiamento', '12.0', '0.75'),
(18, 'Eruzione 1614 Carricante', 'Bianco', 'In questo caso, il colore dorato molto tenue non fa presagire l&apos;esplosione di profumi di frutti maturi e la carica aromatica di fiori bianchi che magicamente trasportano il degustatore alle pendici della montagna etnea. Anche al palato il vino riesce a coniugare morbidezza e grinta gustativa con una bevibilit&agrave; ricca di un fruttato sincero di scorza di limone e mela verde legati ad una mineralit&agrave; trascinante che ne allunga il finale.', 'Sicilia DOC', 2012, '90% Carricante, 10% Riesling', ' Straordinario compagno di piatti a base di pesce di una certa grassezza e comunque ideale per qualunque idea gastronomica pensata in riva al mare', 'Perfetto per un pranzo o una cena fra colleghi', '13.0', '0.75'),
(19, 'Eruzione 1614 Riesling', 'Bianco', 'Un vino unico, primo Riesling sull&apos;Etna e in Sicilia. Colore tenue con riflessi verdi, naso ancora giovane con tutti i precursori tipici della “variet&agrave; dei re”: mela bianca, susina, limone, fiori gialli. Al palato &egrave; perfettamente secco, con una acidit&agrave; non eccessiva. Il gusto &egrave; forse il suo punto di forza in questa fase giovanile, con quel gusto “minerale” sul quale tanti esperti si confrontano per poterlo descrivere.', 'Terre Siciliane IGT', 2013, '100% Riesling', 'La sua acidit&agrave; vibrante e sapidit&agrave; pronunciata sposa bene con tutti i gusti puri: sgombro e dentice, preparati in forno o al vapore, piatti a base di riso e verdure, ortaggi crudi e nelle cucine etniche con i sashimi, o cambiando continente con i quesadilla', 'Ideale per un pranzo in famiglia', '13.0', '1.00'),
(21, 'Brut Metodo Classico', 'Bianco', 'Una sorpresa straordinaria che l&apos;Etna montagna siciliana ci ha regalato grazie alla freschezza del Carricante raccolto leggermente prima del tempo e grazie alle sabbie fini che riescono assieme a generare non solo profumazioni di floreali mai invadenti ma anche un fruttato citrico di grande mineralit&agrave; che supporta un perlage raffinato e persistente. In bocca il vino scorre\r\nmorbidissimo con un sapore delicato di cedro e frutto della passione. Incanto gustativo sorprendente, ricco e punteggiato da aromi di macchia mediterranea sul finale.', 'Sicilia DOC', 2009, '100% Carricante', 'Perfetto per iniziare, o finire, qualunque serata, la sua freschezza acida gli consente di arrivare laddove molti vini non possono osare come una frittura o piatti di marcata untuosit&agrave;', 'Suggerito da regalare ad una persona cara per un&apos;occasione importante', '13.0', '1.50'),
(22, 'Etna Bianco', 'Bianco', 'Le uve di Carricante sull&apos;Etna regalano sempre enormi soddisfazioni perch&eacute; il matrimonio tra le sabbie nere e l&apos;altitudine fornisce aromi articolati e sapori tridimensionali. In questa annata incontriamo note di fiori di acacia bianca, mandorle\r\nfresche e pesche di montagna al palato succoso e pieno con note di susine gialle e fichi d&apos;india.', 'Etna DOC', 2009, '100% Carricante', 'Perfetto con carpacci di pesce e frutta fresca, con primi di pesce, formaggi a pasta filata. &egrave; anche un raffinato aperitivo', 'Perfetto per un aperitivo in compagnia o una cena elegante', '13.0', '1.50'),
(23, 'Etna Rosso', 'Rosso', 'Vaniglia, amarena, fragolina di bosco, un tocco floreale pulitissimo. Bocca carnosa eppure scorrevole. Frutto molto espressivo con incursioni aromatiche pi&ugrave; complesse di sottobosco, mirto e spezie orientali. Vino che inaspettatamente si trasforma in un campione di rosso beverino dalla sapidit&agrave; marcata con un profilo gustativo che fa emergere una traccia ferrosa, di rabarbaro e pepe nero\r\nmolto coinvolgenti.', 'Etna DOC', 2012, '100% Nerello Mascalese', 'Vino da bere a tavola tutti i giorni. La sua duttilit&agrave; gastronomica gli consente di stare accanto sia ai piatti pi&ugrave; semplici, come una pasta al pomodoro, che a quelli pi&ugrave; complessi di carne che di pesce', 'Suggerito per un pranzo in famiglia', '13.0', '1.00'),
(24, 'Mamertino', 'Rosso', 'Una denominazione che affonda le radici nell&apos;antichit&agrave; quando i Mamertini producevano a Milazzo questo vino descritto da Plinio e amato da Giulio Cesare. Colore rosso brillante e intenso con riflessi viola. Naso esplosivo di macchia mediterranea, frutta blu e confettura. Al palato tannicit&agrave; fitta e non eccessiva, ben integrata con il legno.', 'Mamertino DOC', 2010, '60% Nero d&apos;Avola, 40% Nocera', 'Abbinabile con antipasti della casa, primi a base di ragout, carciofi cotti in ogni modo, agnello e cernia in casseruola', 'Suggerito per un pranzo in famiglia', '13.0', '0.75'),
(25, 'Nocera', 'Rosso', 'Il Nocera possiede riflessi rubino profondi e intensi. Al naso &egrave; originale e diverso dal comune: pepe\r\nbianco, geranio e frutta estiva, prugne e fichi di Noto maturi. Al palato &egrave; suadente e morbido, cifra stilistica di questa variet&agrave; siciliana cos&igrave; antica e tutta da scoprire. Ancora giovani le vigne e cos&igrave; anche l&apos;intelaiatura del vino, che resta per&ograve; assolutamente originale.', 'Sicilia DOC', 2013, '100% Nocera', 'Perfetto negli abbinamenti con carni alla brace', 'Suggerito per un pranzo in famiglia', '13.0', '0.75'),
(26, 'Bertarose', 'Ros&eacute;', 'Oggi il Sicilia DOC &ldquo;Bertarose&rdquo; &egrave; stato ripensato e riletto in chiave moderna e attuale, cos&igrave; da risultare fresco ed armonico, perfetto come aperitivo e ideale da sorseggiare per le occasioni pi&ugrave; conviviali. Le due variet&agrave; vitate da cui &egrave; ottenuto vengono vinificate separatamente, e successivamente assemblate e lasciate riposare in acciaio sulle fecce fini per tre mesi. Eclettico e versatile, si lascia bere pi&ugrave; che gradevolmente.', 'Sicia DOC', 2013, 'Molinara 75%, Merlot 25%', 'Eccellente con salumi e primi piatti, &egrave; favoloso se abbinato alla pizza al prosciutto.', 'Rosa cerasuolo con delicati riflessi violacei. Fresco, floreale e fruttato al naso, con richiami alla rosa canina, alla ciliegia e alla fragola. Dimostra di avere carattere e buon corpo al sorso, dove &egrave; caratterizzato da una vivace freschezza e da una gradevole sapidit&agrave;. Lunga la persistenza.     ', '12.0', '0.75'),
(27, 'Ros&eacute; Brut', 'Ros&eacute;', 'Rosato corallo intenso, uno spumante molto profumato di pesca e frutti tropicali, agrumi e fiori bianchi, perlage fine anche al palato dove esprime acidit&agrave;, sapidit&agrave; e un buon corpo.\r\nUno Spumante rosato chiaretto brut metodo charmat, si presenta al calice con un perlage fine, un bel colore rosa corallo intenso, olfatto di pesca e frutti tropicali, agrumi, fiori, palato molto pulito e accarezzato dal perlage, risulta fine e ricco di freschezza e sapidit&agrave;.', 'Sicilia D.O.C.', 2007, 'Groppello, Barbera, Sangiovese e Marzemino', 'Si abbina bene come aperitivo a cocktail di gamberi, acciughe sott&#039;olio, pizzette.', 'Ideale per brindisi e occasioni di festeggiamento', '12.5', '0.75'),
(28, 'L&#039;astore Masseria', 'Ros&eacute;', 'Spumante siciliano notevole, profumato di agrumi, chiodi di garofano, scorza d&#039;arancia, lampone, sorso fresco e piacevole, di particolare equilibrio gustativo, bollicine fini e grande bevibilit&agrave;.\r\nDa uve autoctone Susumaniello, uno spumante rosato biologico che far&agrave; la felicit&agrave; di chi cerca immediatezza, ma anche eleganza e intensit&agrave; di profumo e gusto.\r\nMolto fini le bollicine, che innestano un maggior piacere di assaggiarlo pi&ugrave; volte, ottima la persistenza del gusto, che si rivela, quest&#039;ultimo, molto equilibrato e senza sterzate verso durezze.', 'Sicilia D.O.C.', 2012, 'Susumaniello', 'Da aperitivo, solo o con un cocktail di gamberi e frittatine fredde.\r\n', 'Ideale per brindisi e occasioni di festeggiamento\r\n', '12.5', '0.75');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `annate`
--
ALTER TABLE `annate`
  ADD PRIMARY KEY (`anno`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_user`);

--
-- Indici per le tabelle `vini`
--
ALTER TABLE `vini`
  ADD PRIMARY KEY (`id_wine`),
  ADD KEY `annata` (`annata`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT per la tabella `vini`
--
ALTER TABLE `vini`
  MODIFY `id_wine` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `vini`
--
ALTER TABLE `vini`
  ADD CONSTRAINT `Annata` FOREIGN KEY (`annata`) REFERENCES `annate` (`anno`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
