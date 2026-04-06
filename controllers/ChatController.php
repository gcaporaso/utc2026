<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
//use Orhanerday\OpenAi\OpenAi;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;

//use yii\data\ActiveDataProvider;
//use kartik\dynagrid\models\DynaGridConfig;
//use PhpOffice\PhpWord;
//use PhpOffice\PhpWord\Shared;
//use PhpOffice\PhpWord\Settings;
//use yii\helpers\FileHelper;
//use yii\web\UploadedFile;
//use kekaadrenalin\imap;
//use kekaadrenalin\imap\Mailbox;
//use kekaadrenalin\yii2imap\Imap;

class ChatController extends \yii\web\Controller
{

    public $OPENAI_API_KEY;
    public $AI_MODEL= 'GPT-4o mini';
    # AI_MODEL        =   'gpt-4'

    # Max number of tokens permitted within a conversation exchange via OpenAI API
    private $MAX_TOKENS_ALLOWED      =   3000;
    
    /**
    * 
    * @see CController::init()
    */
    public function init(){
	//Yii::import('application.vendors.*');
        //require_once('@vendor/aicapo/aiconfig.php');
        $this->OPENAI_API_KEY  = getenv('OPENAI_API_KEY');
//        $AI_MODEL == 'GPT-4o mini';
	parent::init();	
    }

public $tools = [
    [
        'type' => 'function',
        'function' => [
            'name'=> 'executeQuery',
            'description'=> 'Esegue una query SQL e restituisce i risultati in formato JSON.',
            'parameters'=> [
                'type'=> 'object',
                'properties'=> [
                    'query'=> ['type' => 'string', 'description' => 'La Query MySQL da eseguire sul database'],
                ],
                'required'=> ['query'],
            ],
        ],
    
    ],
];

public $database_schema_string ="CREATE TABLE `categorie` (
                      `idcategorie` int NOT NULL AUTO_INCREMENT,
                      `descrizione` varchar(255) NOT NULL,
                      PRIMARY KEY (`idcategorie`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
                        CREATE TABLE `cdu` (
                          `idcdu` int NOT NULL AUTO_INCREMENT,
                          `idrich` int NOT NULL,
                          `sez1` varchar(45) NOT NULL,
                          `foglio1` varchar(45) NOT NULL,
                          `particelle1` varchar(255) NOT NULL,
                          `sez2` varchar(45) DEFAULT NULL,
                          `foglio2` varchar(45) DEFAULT NULL,
                          `particelle2` varchar(255) DEFAULT NULL,
                          `sez3` varchar(45) DEFAULT NULL,
                          `foglio3` varchar(45) DEFAULT NULL,
                          `particelle3` varchar(255) DEFAULT NULL,
                          `sez4` varchar(45) DEFAULT NULL,
                          `foglio4` varchar(45) DEFAULT NULL,
                          `particelle4` varchar(255) DEFAULT NULL,
                          `tipodestinatario` int NOT NULL DEFAULT '0',
                          `esenzione` int NOT NULL DEFAULT '0',
                          `NumeroProtocollo` varchar(45) NOT NULL,
                          `DataProtocollo` date NOT NULL,
                          PRIMARY KEY (`idcdu`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
                        CREATE TABLE `commissioni` (
                          `idcommissioni` int NOT NULL AUTO_INCREMENT,
                          `Descrizione` varchar(65) NOT NULL,
                          `NumeroDelibera` int DEFAULT NULL,
                          `DataDelibera` date DEFAULT NULL,
                          `Tipo` int NOT NULL DEFAULT '1' COMMENT 'Tipo di Commissione',
                          `commissioni_categoria_id` int NOT NULL,
                          PRIMARY KEY (`idcommissioni`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
                        CREATE TABLE `committenti` (
                          `committenti_id` int NOT NULL AUTO_INCREMENT,
                          `Cognome` varchar(45) DEFAULT NULL,
                          `Nome` varchar(25) DEFAULT NULL,
                          `DataNascita` date DEFAULT NULL,
                          `RegimeGiuridico_id` int NOT NULL DEFAULT '0',
                          `NOME_COMPOSTO` varchar(45) DEFAULT NULL,
                          `IndirizzoResidenza` varchar(45) DEFAULT NULL,
                          `ComuneResidenza` varchar(25) DEFAULT NULL,
                          `ProvinciaResidenza` varchar(2) DEFAULT NULL,
                          `CodiceFiscale` varchar(25) DEFAULT NULL,
                          `PartitaIVA` varchar(11) DEFAULT NULL,
                          `Email` varchar(255) DEFAULT NULL,
                          `PEC` varchar(255) DEFAULT NULL,
                          `Telefono` varchar(12) DEFAULT NULL,
                          `Cellulare` varchar(15) DEFAULT NULL,
                          `Denominazione` varchar(45) DEFAULT NULL,
                          `ComuneNascita` varchar(45) DEFAULT NULL,
                          `ProvinciaNascita` varchar(2) DEFAULT NULL,
                          `NumeroCivicoResidenza` varchar(45) DEFAULT NULL,
                          `CapResidenza` varchar(10) DEFAULT NULL,
                          `Fax` varchar(45) DEFAULT NULL,
                          PRIMARY KEY (`committenti_id`),
                          KEY `Tipo_Giuridico_index` (`RegimeGiuridico_id`),
                          KEY `Cellulare_index` (`Cellulare`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=279 DEFAULT CHARSET=latin1;
                        CREATE TABLE `componenti_commissioni` (
                          `idcomponenti_commissioni` int NOT NULL AUTO_INCREMENT,
                          `Cognome` varchar(45) NOT NULL,
                          `Nome` varchar(45) NOT NULL,
                          `titolo_id` int NOT NULL DEFAULT '0',
                          `DataNascita` date DEFAULT NULL,
                          `ComuneResidenza` varchar(45) DEFAULT NULL,
                          `ProvinciaResidenza` varchar(2) DEFAULT NULL,
                          `IndirizzoResidenza` varchar(45) DEFAULT NULL,
                          `telefono` varchar(45) DEFAULT NULL,
                          `email` varchar(45) DEFAULT NULL,
                          `cellulare` varchar(45) DEFAULT NULL,
                          `pec` varchar(45) DEFAULT NULL,
                          PRIMARY KEY (`idcomponenti_commissioni`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
                        CREATE TABLE `composizioni` (
                          `idcomposizioni` int NOT NULL AUTO_INCREMENT,
                          `commissioni_id` int NOT NULL,
                          `componenti_id` int NOT NULL,
                          PRIMARY KEY (`idcomposizioni`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
                    CREATE TABLE `edilizia` (
                      `edilizia_id` int NOT NULL AUTO_INCREMENT,
                      `DataProtocollo` date NOT NULL,
                      `NumeroProtocollo` int NOT NULL,
                      `id_committente` int NOT NULL,
                      `id_titolo` int NOT NULL DEFAULT '4',
                      `DescrizioneIntervento` mediumtext,
                      `PROGETTISTA_ARC_ID` int DEFAULT NULL,
                      `PROGETTISTA_ARCHITETTONICO` varchar(45) DEFAULT NULL,
                      `DIR_LAV_ARCH_ID` int DEFAULT NULL,
                      `DIRETTORE_LAVORI_ARCHITETTONICO` varchar(255) DEFAULT NULL,
                      `PROGETTISTA_STR_ID` int DEFAULT NULL,
                      `PROGETTISTA_STRUTTURE` varchar(45) DEFAULT NULL,
                      `DIR_LAV_STR_ID` int DEFAULT NULL,
                      `DIRETTORE_LAVORI_STRUTTURE` varchar(45) DEFAULT NULL,
                      `IMPRESA_ID` int DEFAULT NULL,
                      `IMPRESA` varchar(255) DEFAULT NULL,
                      `CatastoFoglio` int DEFAULT NULL,
                      `CatastoParticella` varchar(45) DEFAULT NULL,
                      `CatastoSub` varchar(25) DEFAULT NULL,
                      `ESITO-UTC` tinyint DEFAULT NULL,
                      `Data_OK` date DEFAULT NULL,
                      `Stato_Pratica_id` int NOT NULL DEFAULT '0',
                      `Latitudine` double DEFAULT NULL,
                      `Longitudine` double DEFAULT NULL,
                      `AutPaesistica` tinyint NOT NULL DEFAULT '0',
                      `Diritti_AP` int DEFAULT NULL,
                      `NumeroTitolo` int DEFAULT NULL,
                      `DataTitolo` date DEFAULT NULL,
                      `Sanatoria` tinyint DEFAULT '0',
                      `NumeroAutorizzazionePaesaggistica` int DEFAULT NULL,
                      `DataAutorizzazionePaesaggistica` date DEFAULT NULL,
                      `COMPATIBILITA_PAESISTICA` tinyint DEFAULT '0',
                      `TitoloOneroso` tinyint DEFAULT '0',
                      `Oneri_Costruzione` decimal(9,2) DEFAULT '0.00',
                      `Oneri_Pagati` decimal(9,2) DEFAULT '0.00',
                      `Data_Inizio_Lavori` date DEFAULT NULL,
                      `Data_Fine_Lavori` date DEFAULT NULL,
                      `CatastoTipo` tinyint(1) DEFAULT '1',
                      `IndirizzoImmobile` varchar(45) DEFAULT NULL,
                      `Collaudatore_id` int DEFAULT NULL,
                      `tipologia_id` int NOT NULL DEFAULT '1',
                      `ditta_id` int DEFAULT NULL,
                      `procuratore_id` int DEFAULT NULL,
                      `inqualitadi` varchar(45) DEFAULT NULL,
                      `Oneri_Urbanizzazione` decimal(9,2) DEFAULT NULL,
                      PRIMARY KEY (`edilizia_id`),
                      KEY `PROT._index` (`NumeroProtocollo`),
                      KEY `committente_id_index` (`id_committente`),
                      KEY `id_titolo_index` (`id_titolo`),
                      KEY `Catasto_-_Foglio_index` (`CatastoFoglio`),
                      KEY `Diritti_AP_index` (`Diritti_AP`),
                      KEY `NUM._TITOLO_index` (`NumeroTitolo`),
                      KEY `NUM._AUT.NE_PAES._index` (`NumeroAutorizzazionePaesaggistica`),
                      KEY `_idx` (`tipologia_id`),
                      CONSTRAINT `tipologia_ind` FOREIGN KEY (`tipologia_id`) REFERENCES `tipologia` (`idtipologia`),
                      CONSTRAINT `titoli_idx` FOREIGN KEY (`id_titolo`) REFERENCES `titoli_edilizia` (`titoli_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=latin1;
                    CREATE TABLE `formagiuridica` (
                      `idformagiuridica` int NOT NULL AUTO_INCREMENT,
                      `descrizione` varchar(255) NOT NULL,
                      PRIMARY KEY (`idformagiuridica`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
                    CREATE TABLE `imprese` (
                      `imprese_id` int NOT NULL AUTO_INCREMENT,
                      `RAGIONE_SOCIALE` varchar(255) NOT NULL,
                      `COGNOME` varchar(12) DEFAULT NULL,
                      `NOME` varchar(25) DEFAULT NULL,
                      `DATA_NASCITA` date DEFAULT NULL,
                      `PROVINCIA_NASCITA` varchar(255) DEFAULT NULL,
                      `NOME_COMPOSTO` varchar(45) DEFAULT NULL,
                      `INDIRIZZO` varchar(45) DEFAULT NULL,
                      `COMUNE_RESIDENZA` varchar(25) DEFAULT NULL,
                      `PROVINCIA_RESIDENZA` varchar(5) DEFAULT NULL,
                      `CODICE_FISCALE` varchar(16) DEFAULT NULL,
                      `PartitaIVA` varchar(11) DEFAULT NULL,
                      `EMAIL` varchar(45) DEFAULT NULL,
                      `PEC` varchar(45) DEFAULT NULL,
                      `Cassa_Edile` varchar(12) DEFAULT NULL,
                      `INPS` varchar(12) DEFAULT NULL,
                      `INAIL` varchar(12) DEFAULT NULL,
                      `Telefono` int DEFAULT NULL,
                      `Cellulare` bigint DEFAULT NULL,
                      `formaGiuridica` int NOT NULL DEFAULT '0',
                      `COMUNE_NASCITA` varchar(45) DEFAULT NULL,
                      PRIMARY KEY (`imprese_id`),
                      KEY `Cassa_Edile_index` (`Cassa_Edile`),
                      KEY `Telefono_index` (`Telefono`),
                      KEY `Cellulare_index` (`Cellulare`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
                    CREATE TABLE `istruttori` (
                      `istruttori_id` int NOT NULL AUTO_INCREMENT,
                      `TIPO` varchar(25) DEFAULT NULL,
                      PRIMARY KEY (`istruttori_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
                    CREATE TABLE `oneri_concessori` (
                      `idoneri` int NOT NULL AUTO_INCREMENT,
                      `edilizia_id` int NOT NULL,
                      `tiporata` int NOT NULL DEFAULT '0',
                      `importodovutorata` decimal(9,2) DEFAULT NULL,
                      `datascadenza` date DEFAULT NULL,
                      `importopagatorata` decimal(9,2) DEFAULT NULL,
                      `datapagamento` date DEFAULT NULL,
                      `ratanumero` int NOT NULL DEFAULT '0',
                      `pagata` tinyint NOT NULL DEFAULT '0',
                      PRIMARY KEY (`idoneri`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=25946 DEFAULT CHARSET=latin1;
                        CREATE TABLE `paesistica` (
                          `idpaesistica` int NOT NULL AUTO_INCREMENT,
                          `NumeroProtocollo` int NOT NULL,
                          `DataProtocollo` date NOT NULL,
                          `idcommittente` int NOT NULL,
                          `idtipo` int NOT NULL DEFAULT '1',
                          `DescrizioneIntervento` mediumtext NOT NULL,
                          `Progettista_ID` int DEFAULT NULL,
                          `Direttore_Lavori_ID` int DEFAULT NULL,
                          `CatastoFoglio` varchar(45) DEFAULT NULL,
                          `CatastoParticella` varchar(45) DEFAULT NULL,
                          `CatastoSub` varchar(45) DEFAULT NULL,
                          `StatoPratica` int NOT NULL DEFAULT '1',
                          `NumeroAutorizzazione` int DEFAULT NULL,
                          `DataAutorizzazione` date DEFAULT NULL,
                          `Compatibilita` tinyint(1) DEFAULT '0',
                          `IndirizzoImmobile` varchar(45) DEFAULT NULL,
                          `Impresa_ID` int DEFAULT NULL,
                          `Indennita` double NOT NULL DEFAULT '0',
                          `TipoCatasto` tinyint(1) DEFAULT '0',
                          `InviatoSoprintendenza` tinyint(1) DEFAULT '0',
                          `InviatoRegione` tinyint(1) DEFAULT '0',
                          `Latitudine` varchar(45) DEFAULT NULL,
                          `Longitudine` varchar(45) DEFAULT NULL,
                          `IndPagata` double DEFAULT '0',
                          `FileAutorizzazione` tinyint(1) NOT NULL DEFAULT '0',
                          `Edilizia_ID` int DEFAULT NULL,
                          PRIMARY KEY (`idpaesistica`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=1357 DEFAULT CHARSET=latin1;
                        CREATE TABLE `pareri_commissioni` (
                          `idpareri_commissioni` int NOT NULL AUTO_INCREMENT,
                          `commissioni_id` int NOT NULL,
                          `pratica_id` int NOT NULL,
                          `seduta_id` int NOT NULL,
                          `tipoparere_id` int NOT NULL,
                          `testoparere` mediumtext NOT NULL,
                          PRIMARY KEY (`idpareri_commissioni`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=latin1;
                        CREATE TABLE `ruoli_tecnici` (
                          `idruoli_tecnici` int NOT NULL AUTO_INCREMENT,
                          `ruolo` varchar(45) NOT NULL,
                          PRIMARY KEY (`idruoli_tecnici`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
                        CREATE TABLE `sedute_commissioni` (
                          `idsedute_commissioni` int NOT NULL AUTO_INCREMENT,
                          `commissione_id` int NOT NULL DEFAULT '1',
                          `dataseduta` date NOT NULL,
                          `statoseduta` int NOT NULL DEFAULT '1',
                          `presenze` int NOT NULL,
                          `orarioconvocazione` time NOT NULL DEFAULT '18:00:00',
                          `orarioinizio` time DEFAULT NULL,
                          `orariofine` time DEFAULT NULL,
                          `numero` int NOT NULL,
                          PRIMARY KEY (`idsedute_commissioni`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
                        CREATE TABLE `sismica` (
                          `sismica_id` int NOT NULL AUTO_INCREMENT,
                          `TrasmissioneGenioCivile` int NOT NULL DEFAULT '0',
                          `Data_Collaudo` date DEFAULT NULL,
                          `Ubicazione` varchar(150) DEFAULT NULL,
                          `PubblicazioneALBO` int NOT NULL DEFAULT '0',
                          `Variante` int NOT NULL DEFAULT '0',
                          `PAGATO_COMMISSIONE` int NOT NULL DEFAULT '0',
                          `CatastoParticelle` varchar(45) DEFAULT NULL,
                          `DIR_LAV_STR_ID` int DEFAULT NULL,
                          `DD_LL_ARCH_ID` int DEFAULT NULL,
                          `pratica_id` int DEFAULT NULL,
                          `TipoTitolo` int NOT NULL DEFAULT '1',
                          `muratura_armata` int NOT NULL DEFAULT '0',
                          `Protocollo` int NOT NULL,
                          `AbitatoConsolidare` int DEFAULT NULL,
                          `DestinazioneDuso` varchar(45) DEFAULT NULL,
                          `ClassificazioneSismica` int DEFAULT NULL,
                          `NumeroAUTORIZZAZIONE` int DEFAULT NULL,
                          `COLLAUDATORE_ID` int DEFAULT NULL,
                          `CatastoSub` varchar(45) DEFAULT NULL,
                          `IMPRESA_ID` int DEFAULT NULL,
                          `Sopraelevazione` int DEFAULT NULL,
                          `societa` int DEFAULT NULL,
                          `cemento_precompresso` int NOT NULL DEFAULT '0',
                          `CatastoFoglio` varchar(45) DEFAULT NULL,
                          `TipoCommittente` int DEFAULT NULL,
                          `DataProtocollo` date NOT NULL,
                          `StrutturaAltro` int NOT NULL DEFAULT '0',
                          `muratura_ordinaria` int NOT NULL DEFAULT '0',
                          `ente_pubblico` int DEFAULT NULL,
                          `IstruttoreID` int DEFAULT NULL,
                          `TipoProcedimento` int NOT NULL DEFAULT '0',
                          `DataAUTORIZZAZIONE` date DEFAULT NULL,
                          `GeologoID` int DEFAULT NULL,
                          `committenti_id` int NOT NULL,
                          `PROG_STR_ID` int DEFAULT NULL,
                          `AccelerazioneAg` decimal(4,2) DEFAULT NULL,
                          `Ampliamento` int NOT NULL DEFAULT '0',
                          `Fine_Lavori` date DEFAULT NULL,
                          `DescrizioneLavori` varchar(255) NOT NULL,
                          `ImportoContributo` double DEFAULT NULL,
                          `PROG_ARCH_ID` int DEFAULT NULL,
                          `Data_Strutture_Ultimate` date DEFAULT NULL,
                          `Ritiro` int NOT NULL DEFAULT '0',
                          `Integrazione` int NOT NULL DEFAULT '0',
                          `Inizio_Lavori` date DEFAULT NULL,
                          `CatastoTipo` int DEFAULT NULL,
                          `Articolo65` int DEFAULT NULL,
                          `DataVersamentoContributo` date DEFAULT NULL,
                          `struttura_metallica` int NOT NULL DEFAULT '0',
                          `struttura_legno` int NOT NULL DEFAULT '0',
                          `InteresseRegionale` int NOT NULL DEFAULT '0',
                          `InteresseStatale` int NOT NULL DEFAULT '0',
                          `cemento_armato` int NOT NULL DEFAULT '0',
                          `TipoDenuncia` int NOT NULL DEFAULT '1',
                          `NumeroProtocolloAutorizzazione` int DEFAULT NULL,
                          `StatoPratica` int NOT NULL DEFAULT '5',
                          `ImportoPagato` double DEFAULT '0',
                          PRIMARY KEY (`sismica_id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
                        CREATE TABLE `soggetti_edilizia_imprese` (
                          `ididsoggettiimprese` int NOT NULL,
                          `edilizia_id` int NOT NULL,
                          `imprese_id` int NOT NULL,
                          PRIMARY KEY (`ididsoggettiimprese`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                        CREATE TABLE `soggetti_edilizia_tecnici` (
                          `ididsoggettitecnici` int NOT NULL,
                          `edilizia_id` int NOT NULL,
                          `tecnici_id` int NOT NULL,
                          PRIMARY KEY (`ididsoggettitecnici`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                        CREATE TABLE `soggetti_edilizia_titolari` (
                          `idsoggettititolari` int NOT NULL,
                          `edilizia_id` int NOT NULL,
                          `committenti_id` int NOT NULL,
                          PRIMARY KEY (`idsoggettititolari`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                        CREATE TABLE `soggetti_sismica_tecnici` (
                          `idsoggetti_sismica_tecnici` int NOT NULL AUTO_INCREMENT,
                          `sismica_id` int NOT NULL,
                          `tecnici_id` int NOT NULL,
                          `ruolo_id` int NOT NULL,
                          `data_inizio` date DEFAULT NULL,
                          `data_fine` date DEFAULT NULL,
                          PRIMARY KEY (`idsoggetti_sismica_tecnici`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;
                        CREATE TABLE `soggetti_sismica_titolari` (
                          `idsoggettititolari` int NOT NULL AUTO_INCREMENT,
                          `sismica_id` int NOT NULL,
                          `committenti_id` int NOT NULL,
                          `titolo_id` int NOT NULL,
                          `percentuale` decimal(5,2) NOT NULL DEFAULT '100.00',
                          PRIMARY KEY (`idsoggettititolari`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
                        CREATE TABLE `stato_edilizia` (
                          `idstato_edilizia` int NOT NULL AUTO_INCREMENT,
                          `descrizione` varchar(45) NOT NULL,
                          PRIMARY KEY (`idstato_edilizia`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
                        CREATE TABLE `stato_sedute` (
                          `idstato_sedute` int NOT NULL AUTO_INCREMENT,
                          `descrizione` varchar(45) NOT NULL,
                          PRIMARY KEY (`idstato_sedute`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
                        CREATE TABLE `tecnici` (
                          `tecnici_id` int NOT NULL AUTO_INCREMENT,
                          `COGNOME` varchar(12) DEFAULT NULL,
                          `NOME` varchar(25) DEFAULT NULL,
                          `COMUNE_NASCITA` varchar(255) DEFAULT NULL,
                          `PROVINCIA_NASCITA` varchar(255) DEFAULT NULL,
                          `DATA_NASCITA` date DEFAULT NULL,
                          `ALBO` varchar(25) DEFAULT NULL,
                          `idtitolo` int NOT NULL DEFAULT '2',
                          `PROVINCIA_ALBO` varchar(12) DEFAULT NULL,
                          `NUMERO_ISCRIZIONE` varchar(12) DEFAULT NULL,
                          `NOME_COMPOSTO` varchar(255) DEFAULT NULL,
                          `INDIRIZZO` varchar(45) DEFAULT NULL,
                          `COMUNE_RESIDENZA` varchar(25) DEFAULT NULL,
                          `PROVINCIA_RESIDENZA` varchar(5) DEFAULT NULL,
                          `CODICE_FISCALE` varchar(25) DEFAULT NULL,
                          `P_IVA` varchar(255) DEFAULT NULL,
                          `TELEFONO` varchar(12) DEFAULT NULL,
                          `FAX` varchar(12) DEFAULT NULL,
                          `CELLULARE` varchar(12) DEFAULT NULL,
                          `EMAIL` varchar(255) DEFAULT NULL,
                          `PEC` varchar(45) DEFAULT NULL,
                          `Denominazione` varchar(45) DEFAULT NULL,
                          `formaGiuridica` int NOT NULL DEFAULT '0' COMMENT '0 = Professionista Singolo\n1 = Società Ingegneria\n2 = Società Professionisti\n3 = Professionisti Associati\n4 = Consorzio\n5 = Raggruppamento Temporaneo',
                          `cap_residenza` varchar(8) DEFAULT NULL,
                          PRIMARY KEY (`tecnici_id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
                        CREATE TABLE `tipo_commissioni` (
                          `idtipo_commissioni` int NOT NULL AUTO_INCREMENT,
                          `descrizione` varchar(45) NOT NULL,
                          PRIMARY KEY (`idtipo_commissioni`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
                        CREATE TABLE `tipo_procedimento_sismica` (
                          `idtipo` int NOT NULL AUTO_INCREMENT,
                          `descrizione` varchar(45) DEFAULT NULL,
                          PRIMARY KEY (`idtipo`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
                        CREATE TABLE `tipologia` (
                          `idtipologia` int NOT NULL AUTO_INCREMENT,
                          `Categoria` varchar(25) NOT NULL,
                          `DESCRIZIONE` varchar(800) NOT NULL,
                          `Normativa` varchar(255) DEFAULT '',
                          PRIMARY KEY (`idtipologia`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
                        CREATE TABLE `tipoparere` (
                          `idtipoparere` int NOT NULL AUTO_INCREMENT,
                          `esitoparere` varchar(45) NOT NULL,
                          PRIMARY KEY (`idtipoparere`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
                        CREATE TABLE `titoli_edilizia` (
                          `titoli_id` int NOT NULL AUTO_INCREMENT,
                          `TITOLO` varchar(15) DEFAULT NULL,
                          `DESCRIZIONE` varchar(255) DEFAULT NULL,
                          PRIMARY KEY (`titoli_id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
                        CREATE TABLE `titoli_paesistica` (
                          `idtitoli_paesistica` int NOT NULL AUTO_INCREMENT,
                          `descrizione` varchar(125) NOT NULL,
                          PRIMARY KEY (`idtitoli_paesistica`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
                        CREATE TABLE `titoli_possesso` (
                          `idtitoli_possesso` int NOT NULL AUTO_INCREMENT,
                          `descrizione` varchar(45) NOT NULL,
                          PRIMARY KEY (`idtitoli_possesso`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
                        CREATE TABLE `titoli_sismica` (
                          `idtitoli_sismica` int NOT NULL AUTO_INCREMENT,
                          `descrizione` varchar(45) DEFAULT NULL,
                          PRIMARY KEY (`idtitoli_sismica`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
                        CREATE TABLE `titolo_componente` (
                          `idtitolo_componente` int NOT NULL AUTO_INCREMENT,
                          `titolo` varchar(45) NOT NULL,
                          `abbr_titolo` varchar(8) NOT NULL DEFAULT 'Sig.',
                          PRIMARY KEY (`idtitolo_componente`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
                        Dove:
                        la tabella 'edilizia' contiene le pratiche edilizie di tipo indicato nella tabella 'tipologia' e presentate dai richiedenti (tabella 'committenti')
                        la tabella 'sismica' contiene le pratiche sismiche di tipo indicato nella tabella 'tipo_procedimento_sismica' e presentate dai richiedenti (tabella 'committenti')
                        la tabella 'paesistica' contiene le pratiche paesaggistiche di tipo indicato nella tabella 'titoli_paesistica' e presentate dai richiedenti (tabella 'committenti')
                        la tabella 'commissioni' contiene le commissioni del tipo indicato nella tabella 'tipo_commissioni' e composta dai soggetti inclusi nella tabella 'componenti_commissioni'
                        la tabella 'oneri_concessori' contiene gli oneri concessori delle varie pratiche edilizie
                        RELAZIONI: 
                        edilizia.id_committente => committenti.committenti_id
                        tipologia.idtipologia' => edilizia.tipologia_id
                        edilizia.id_titolo=>titoli_edilizia.titoli_id";
    
    function debug_to_console($data) {
//        $output = $data;
//        if (is_array($output)) {
//            
//            $output = implode(',', $output);
//        } else {
        echo "<script>console.log('Debug Objects: " . var_dump($data) . "' );</script>";
        //echo "debug:". $output;
//        }
    }
 
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
    
    
    function jsontohtmlTable($json) {
        $data = json_decode($json, true);
        $tabella="";
            if (isset($data['data'])) {
                $tabella = '<table border="1">';
                echo '<tr>';
                foreach ($data['data'][0] as $key => $value) {
                    $tabella=$tabella . '<th>' . htmlspecialchars($key) . '</th>';
                }
                $tabella=$tabella . '</tr>';

                foreach ($data['data'] as $row) {
                    $tabella=$tabella . '<tr>';
                    foreach ($row as $value) {
                        $tabella=$tabella . '<td>' . htmlspecialchars($value) . '</td>';
                    }
                    $tabella=$tabella . '</tr>';
                }

                $tabella=$tabella . '</table>';
            } else {
                $tabella = 'Errore nella risposta JSON.';
            }
        return $tabella;
    }
    
    
//    modo chiaro. Rispondi nel seguente modo:
//                        Metti prima un titolo che riguarda la categoria di dati estratti (EDILIZIA, PAESAGGISTICA, SISMICA) poi vai a capo.
//                        Metti poi una riga con ogni record dati restituito e poi vai a capo, in modo disorsivo e sintetico tipo:
//                        Pratica presentata in data ... protocollo .... avente ad oggetto ....
            
    public function sendRequest($msg)
    {
        $client = new Client();
        //$this->debug_to_console($this->OPENAI_API_KEY);
//        echo "<script>console.log({$this->OPENAI_API_KEY});</script>";
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://api.openai.com/v1/chat/completions')
            ->setHeaders([
                'Authorization' => 'Bearer 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',
                'Content-Type' => 'application/json',
            ])
            ->setContent(json_encode([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ["role" => "system","content" => 'Devi eseguire tre fasi: 
                        1. Scrivi una query MySQL sicura e ben formattata per soddisfare la richiesta dell’utente. 
                        2. Usa il tool `executeQuery` per eseguire la query. 
                        3. Usa i dati ricevuti dal database per rispondere all’utente in forma tabellare 
                        Alla fine vai a capo e metti una nota finale.
                        Non inserire caratteri speciali nella query quali in modo che sia eseguibile con il comando Yii::$app->db->createCommand($query)!
                        Nel costruire la query tieni in conto che la struttura del dadabase MySQL è la seguente:' . $this->database_schema_string],
                    ["role" => "user", "content" => $msg]
                ],
                'tools'=>$this->tools,
                'tool_choice'=>"auto",
                'temperature' => 0.7,
                'max_tokens' => 3000,
            ]))
            ->send();

//        if ($response->isOk) {
////            return $this->processResponse($response->data);
////            $message = $data['choices'][0]['message']; 
//            return ['response' => $response->data['choices'][0]['message']];
//        } else {
//            $res = $response->data['error']['message'] ? $response->data['error']['message'] :'Errore sconosciuto.';
//            return ['response' => $res];
//        }
        
        if ($response->isOk) {
            return $this->handleResponse($response->data);
        } else {
            return ['error' => isset($response->data['error']['message']) ? $response->data['error']['message'] : 'Errore sconosciuto.'];
        }
        
        
        
        
        
    }
    
    
     /**
     * Gestisce la risposta di OpenAI e verifica se è richiesta una funzione.
     *
     * @param array $data La risposta JSON di OpenAI
     * @return array La risposta processata
     */
    private function handleResponse($data)
    {
         if (!isset($data['choices'][0]['message'])) {
            return ['error' => 'Risposta vuota da OpenAI.'];
        }
        //$this->debug_to_console($data);
        $message = $data['choices'][0]['message'];
        //$this->console_log($message);
        // Controlliamo se OpenAI ha chiamato executeQuery
        if (!empty($message['tool_calls'])) {
            //$this->debug_to_console('2-> tool_calls non è vuoto!');
            foreach ($message['tool_calls'] as $toolCall) {
                //$this->debug_to_console($toolCall['function']['arguments']);
                if ($toolCall['function']['name'] == 'executeQuery') {
                    $arguments =$toolCall['function']['arguments'];
                    $query = json_decode($arguments)->{'query'};
                    //$this->debug_to_console($query);
                    //$this->debug_to_console($query);
                    //$this->debug_to_console($query);
                    //$this->console_log($query);
                    $queryResults = $this->executeQuery($query);
                    //$this->debug_to_console($queryResults);
                    //$this->console_log($queryResults);
                    // Ora chiamiamo OpenAI con i dati ricevuti
                    return $this->generateFinalResponse($queryResults);
                } else {
                     if (isset($toolCall['function']['arguments']['query'])) {
                        //$this->debug_to_console($toolCall['function']['name']);     
                     } else {
                        //$this->debug_to_console($toolCall['function']['arguments']);     
                     }
                }
            }
        } else {
            $this->debug_to_console($message['tool_calls']);
        }

        return [
            'response' => isset($message['content']) ? $message['content'] : 'Nessuna risposta ricevuta.',
        ];
    }
    
    
  /**
     * Esegue una query SQL sul database Yii2 e restituisce i risultati.
     *
     * @param string $query La query SQL da eseguire
     * @return array I risultati della query
     */
    private function executeQuery($query)
    {
        try {
            $command = Yii::$app->db->createCommand($query);
            return $command->queryAll();
        } catch (Exception $e) {
            return ['error' => 'Errore SQL: ' . $e->getMessage()];
        }
    } 
    
    
     /**
     * Chiede a OpenAI di generare una risposta testuale basata sui dati della query.
     *
     * @param array $queryResults I risultati della query SQL
     * @return array La risposta testuale generata
     */
    private function generateFinalResponse($queryResults)
    {
        $client = new Client();

        $payload = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Formatta i dati del database in una risposta in formato testuale. Rispondi nel seguente modo:
                        Gentile utente ho trovato per te le seguenti informazioni:
                        Metti prima un titolo che riguarda la categoria di dati estratti (PRATICHE EDILIZIE, PRATICHE PAESAGGISTICHE, PRATICHE SISMICHE) poi vai a capo.
                        Metti poi una riga con ogni record dati restituito e poi vai a capo, in modo disorsivo e sintetico tipo:
                        Pratica presentata in data ... protocollo .... avente ad oggetto .....
                        Rispondi con un testo formattato su più righe. Usa interruzioni di riga per separare le sezioni.'],
                ['role' => 'assistant', 'content' => json_encode($queryResults)],
            ],
            'temperature' => 0.7,
            'max_tokens' => 3000,
        ];

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://api.openai.com/v1/chat/completions')
            ->setHeaders([
                'Authorization' => 'Bearer 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',
                'Content-Type' => 'application/json',
            ])
            ->setContent(json_encode($payload))
            ->send();

        if ($response->isOk) {
            return [
                'response' => isset($response->data['choices'][0]['message']['content']) ? $response->data['choices'][0]['message']['content'] : 'Nessuna risposta formattata.',
            ];
        } else {
            return ['error' => 'Errore nella risposta finale.'];
        }
    }
    
    
    
    
    
    
    
    
    
    
 /**
     * Analizza la risposta di OpenAI per identificare chiamate a funzioni
     * @param array $data Risposta JSON decodificata dall'API
     * @return array Risultato elaborato
     */
private function processResponse($data)
{
    $message = null;
    if ($data['choices'][0]['message']) {
        $message = $data['choices'][0]['message'];   
    }
        

        if (!$message) {
            return ['response' => 'Risposta vuota da OpenAI.'];
        }

//        // Se il modello ha chiamato un tool (funzione)
//        if (!empty($message['tool_calls'])) {
//            return [
//                'function_call' => $message['tool_calls'],
//                'raw_response' => $message,
//            ];
//        }

        // Restituisce la risposta normale
        if ($message['content']) {
            return ['response' => $message['content']];            
        } else {
            return ['response' => 'Nessuna risposta ricevuta.'];            
        }

}
   
    
       
    

    
function actionChat()
{
    
    if (Yii::$app->request->isAjax) {
        $data = Yii::$app->request->post();
        $msg= $data['msguser'];
        //$prompt = Yii::$app->request->post('msg', 'Ciao, come stai?');
        $response = $this->sendRequest($msg);

        return $this->asJson($response);
        //return $response; //->choices[0]->message->content; //$this->asJson(['response' => $response]);        
    }

} 
    
    
function ask_mysql_database($sql) {
    //$sql = 'insert into news (news, display) values (:news, :display)';
    //$parameters = array(':user_id'=>'', ':created' => date('Y-m-d H:i:s'));
    //Yii::app()->db->createCommand($sql)->execute($parameters);
    $result = Yii::$app->db->createCommand($sql)->queryAll();
    return $result;
}     
    
    
    
    
    
    
    
/**
     * Visualizza la Homepage della email
     *
     * @return string
     */
//    public function actionIndex()
//    {
//        return $this->render('qchat');
//    }
    
    public function actionQchat()
    {
        $this->layout = 'main';
        return $this->render('qchat');
    }
    
    
     public function actionQuery()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userPrompt = $_POST['userPrompt'];
            $sqlQuery = generateSQLQuery($userPrompt);

            echo '<h2>Generated SQL Query:</h2>';
            echo '<pre>' . htmlspecialchars($sqlQuery) . '</pre>';

            try {
                // Execute the generated SQL query
                $stmt = $pdo->query($sqlQuery);

                // Fetch all results
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Display the results
                if ($results) {
                    echo '<h2>Query Results:</h2>';
                    echo '<table border="1">';
                    echo '<tr>';
                    foreach (array_keys($results[0]) as $column) {
                        echo '<th>' . htmlspecialchars($column) . '</th>';
                    }
                    echo '</tr>';
                    foreach ($results as $row) {
                        echo '<tr>';
                        foreach ($row as $value) {
                            echo '<td>' . htmlspecialchars($value) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No results found.</p>';
                }
            } catch (PDOException $e) {
                echo 'Query failed: ' . $e->getMessage();
            }
        }
         

        return $this->render('qchat');
    }
   
    
    
public function actionQuery2()
{
    if (Yii::$app->request->isAjax) {
        $datas = Yii::$app->request->post();
        $open_ai = new OpenAi($this->OPENAI_API_KEY);
        $msg= $datas['msguser'];
        $chat = $open_ai->completion([
           'model' => 'text-davinci-002',
           'tools' => $tools,
           'messages' => [
               [
                   "role" => "system",
                   "content" => "Sei un esperto di Query SQL. Genera una MySQL query basata sull'imput dell'utente."
               ],
               ["role" => "user", "content" => $msg]
        //           [
        //               "role" => "user",
        //               "content" => "Who won the world series in 2020?"
        //           ],
        //           [
        //               "role" => "assistant",
        //               "content" => "The Los Angeles Dodgers won the World Series in 2020."
        //           ],
        //           [
        //               "role" => "user",
        //               "content" => "Where was it played?"
        //           ],
           ],
           'temperature' => 1.0,
           'max_tokens' => 300,
           'frequency_penalty' => 0,
           'presence_penalty' => 0,
        ]);
        var_dump($chat);
        echo "<br>";
        echo "<br>";
        echo "<br>";

//        $d = json_decode($chat,true);
//        //echo "<script>console.log({$d['choices'][0]});</script>";
//        //return $d->choices[0]->message->content;
//        //return $d['choices'][0]['message']['content']; 
//        //$d1 = array_values($d);
//        //print_r(each($d));  
////        echo implode(
////            "\n", 
////            array_map(
////                 function ($k, $v) { 
////                     return "$k is at $v"; 
////                 }, 
////                 array_keys($d), 
////                 array_values($d)
////            )
////        );
//        //echo "<script>console.log({$d1[0]});</script>";
//        //echo "<script>console.log({$d1[1]});</script>";
//       return $d->choices[0]->message->content;
    }


}
 
public function actionQuery3()
{

if (Yii::$app->request->isAjax) {
    $datas = Yii::$app->request->post();
            
    $msg= $datas['msguser'];
    set_time_limit(360);
    
    $url = "https://api.openai.com/v1/chat/completions";
    
    $data = [
        "model" => $AI_MODEL, //"gpt-4o", // Sostituisci con il modello desiderato
        "messages" => [
            ["role" => "system", "content" => "Sei un esperto di Query SQL. Genera una MySQL query basata sull'imput dell'utente."],
            ["role" => "user", "content" => $msg]
        ],
        "temperature" => 0.7,
        "max_tokens" => $MAX_TOKENS_ALLOWED
    ];
    
    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n" .
                         "Authorization: Bearer " . $OPENAI_API_KEY . "\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];
    try {
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === FALSE) {
            return "Errore nella richiesta all'API";
        }
        $responseData = json_decode($response, true);
        //return $responseData['choices'][0]['message']['content'] ?? "Errore nella risposta dell'API";
        return $responseData['choices'][0]['message']['content'];    
    } catch (Exception $ex) {
        return "Errore nella risposta dell'API:{$ex}";
    }
    
    
    }
}
    
    
    
   
 
}