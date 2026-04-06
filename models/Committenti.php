<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Committente".
 *
 * @property int $idcommittente
 * @property string $DenominazioneCognome
 * @property string $Nome
 * @property string $ComuneNascita
 * @property string $ProvinciaNascita
 * @property string $DataNascita
 * @property string $CodiceFiscale
 * @property string $PartitaIVA
 * @property string $ResidenzaIndirizzo
 * @property string $ResidenzaCivico
 * @property string $ResidenzaComune
 * @property string $ResidenzaProvincia
 * @property string $ResidenzaCAP
 * @property string $Telefono
 * @property string $Fax
 * @property string $Email
 * @property string $PEC
 * @property string $Cellulare
 * @property int $Rappresentante
 * @property string $DenominazioneDitta
 * @property string $CodiceFiscaleDitta
 * @property string $PartitaIVADitta
 * @property string $ComuneCCIAA
 * @property string $ProvinciaCCIAA
 * @property string $NumeroCCIAA
 * @property string $ComuneSedeLegale
 * @property string $ProvinciaSedeLegale
 * @property string $IndirizzoSedeLegale
 * @property string $EmailSedeLegale
 * @property string $PECSedeLegale
 * @property string $CAPSedeLegale
 * @property string $CellulareSedeLegale
 * @property string $TelefonoSedeLegale
 * @property int $Procuratore
 * @property string $CognomeProcuratore
 * @property string $NomeProcuratore
 * @property string $CodiceFiscaleProcuratore
 * @property string $ComuneNascitaProcuratore
 * @property string $ProvinciaNascitaProcuratore
 * @property string $StatoNascitaProcuratore
 * @property string $DataNascitaProcuratore
 * @property string $ComuneResidenzaProcuratore
 * @property string $ProvinciaResidenzaProcuratore
 * @property string $StatoResidenzaProcuratore
 * @property string $IndirizzoResidenzaProcuratore
 * @property string $EmailProcuratore
 * @property string $PECProcuratore
 * @property string $TelefonoProcuratore
 * @property string $CellulareProcuratore
 */
class Committenti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'committenti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
//        return [
//            [['DataNascita', 'DataNascitaProcuratore'], 'safe'],
//            [['Rappresentante', 'Procuratore'], 'integer'],
//            [['Cognome', 'Nome', 'ComuneNascita', 'ProvinciaNascita', 'CodiceFiscale', 'PartitaIVA', 'ResidenzaIndirizzo', 'ResidenzaCivico', 'ResidenzaComune', 'Email', 'PEC', 'DenominazioneDitta', 'ComuneCCIAA', 'ComuneSedeLegale', 'IndirizzoSedeLegale', 'EmailSedeLegale', 'PECSedeLegale', 'CognomeProcuratore', 'NomeProcuratore', 'ComuneNascitaProcuratore', 'ComuneResidenzaProcuratore', 'IndirizzoResidenzaProcuratore', 'EmailProcuratore', 'PECProcuratore'], 'string', 'max' => 45],
//            [['ResidenzaProvincia', 'ProvinciaCCIAA', 'ProvinciaSedeLegale', 'ProvinciaNascitaProcuratore', 'ProvinciaResidenzaProcuratore'], 'string', 'max' => 2],
//            [['ResidenzaCAP', 'CAPSedeLegale'], 'string', 'max' => 8],
//            [['Telefono', 'Fax', 'Cellulare', 'NumeroCCIAA', 'CellulareSedeLegale', 'TelefonoSedeLegale', 'TelefonoProcuratore', 'CellulareProcuratore'], 'string', 'max' => 10],
//            [['CodiceFiscaleDitta', 'CodiceFiscaleProcuratore'], 'string', 'max' => 16],
//            [['PartitaIVADitta'], 'string', 'max' => 11],
//            [['StatoNascitaProcuratore', 'StatoResidenzaProcuratore'], 'string', 'max' => 5],
//            [['Cognome','Nome'], 'required', 'whenClient' => function($model) {
//                return $model->RegimeGiuridico_id == 0;
//                }, 'enableClientValidation' => false],
//            ['DenominazioneDitta', 'required', 'whenClient' => function($model) {
//                return $model->RegimeGiuridico_id == 1;
//                }, 'enableClientValidation' => false],
//        ];
                
        return [
            [['DataNascita', 'DataNascitaProcuratore','nomeCompleto'], 'safe'],
            [['Cognome', 'Nome', 'ComuneNascita', 'ProvinciaNascita', 'IndirizzoResidenza', 'NumeroCivicoResidenza', 'ComuneResidenza', 'Email', 'PEC'], 'string', 'max' => 45],
            [['ProvinciaResidenza'], 'string', 'max' => 2],
            [['CapResidenza'], 'string', 'max' => 8],
            [['Telefono', 'Fax', 'Cellulare'], 'string', 'max' => 10],
            ['RegimeGiuridico_id','required'],
            ['PartitaIVA','match', 'pattern' => '/^[0-9]{11}$/i'],
            ['Cognome','validateCognome','skipOnEmpty' => false, 'skipOnError' => false],
            ['Nome','validateNome','skipOnEmpty' => false, 'skipOnError' => false],
            ['Denominazione', 'validateDenominazione', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['CodiceFiscale', 'match', 'pattern' => '/^[A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST][0-9LMNPQRSTUV]{2}[A-Z][0-9LMNPQRSTUV]{3}[A-Z]$/i'],
            [['Email','PEC'],'email'],
            [['ProvinciaResidenza','ProvinciaNascita'],'match', 'pattern' => '/^[A-Z]{2}$/i','message' => 'Specifica solo la Sigla della provincia. max due caratteri']
        ];
    }

//    /**
//     * {@inheritdoc}  
//     */
//    public function attributeLabels()
//    {
//        return [
//            'idcommittente' => 'Idcommittente',
//            'Cognome' => 'Cognome',
//            'Nome' => 'Nome',
//            'ComuneNascita' => 'Comune Nascita',
//            'ProvinciaNascita' => 'Provincia Nascita',
//            'DataNascita' => 'Data Nascita',
//            'CodiceFiscale' => 'Codice Fiscale',
//            'PartitaIVA' => 'Partita Iva',
//            'ResidenzaIndirizzo' => 'Residenza Indirizzo',
//            'ResidenzaCivico' => 'Residenza Civico',
//            'ResidenzaComune' => 'Residenza Comune',
//            'ResidenzaProvincia' => 'Residenza Provincia',
//            'ResidenzaCAP' => 'Residenza Cap',
//            'Telefono' => 'Telefono',
//            'Fax' => 'Fax',
//            'Email' => 'Email',
//            'PEC' => 'Pec',
//            'Cellulare' => 'Cellulare',
//            'Rappresentante' => 'Rappresentante',
//            'DenominazioneDitta' => 'Denominazione Ditta',
//            'CodiceFiscaleDitta' => 'Codice Fiscale Ditta',
//            'PartitaIVADitta' => 'Partita Ivaditta',
//            'ComuneCCIAA' => 'Comune Cciaa',
//            'ProvinciaCCIAA' => 'Provincia Cciaa',
//            'NumeroCCIAA' => 'Numero Cciaa',
//            'ComuneSedeLegale' => 'Comune Sede Legale',
//            'ProvinciaSedeLegale' => 'Provincia Sede Legale',
//            'IndirizzoSedeLegale' => 'Indirizzo Sede Legale',
//            'EmailSedeLegale' => 'Email Sede Legale',
//            'PECSedeLegale' => 'Pecsede Legale',
//            'CAPSedeLegale' => 'Capsede Legale',
//            'CellulareSedeLegale' => 'Cellulare Sede Legale',
//            'TelefonoSedeLegale' => 'Telefono Sede Legale',
//            'Procuratore' => 'Procuratore',
//            'CognomeProcuratore' => 'Cognome Procuratore',
//            'NomeProcuratore' => 'Nome Procuratore',
//            'CodiceFiscaleProcuratore' => 'Codice Fiscale Procuratore',
//            'ComuneNascitaProcuratore' => 'Comune Nascita Procuratore',
//            'ProvinciaNascitaProcuratore' => 'Provincia Nascita Procuratore',
//            'StatoNascitaProcuratore' => 'Stato Nascita Procuratore',
//            'DataNascitaProcuratore' => 'Data Nascita Procuratore',
//            'ComuneResidenzaProcuratore' => 'Comune Residenza Procuratore',
//            'ProvinciaResidenzaProcuratore' => 'Provincia Residenza Procuratore',
//            'StatoResidenzaProcuratore' => 'Stato Residenza Procuratore',
//            'IndirizzoResidenzaProcuratore' => 'Indirizzo Residenza Procuratore',
//            'EmailProcuratore' => 'Email Procuratore',
//            'PECProcuratore' => 'Pecprocuratore',
//            'TelefonoProcuratore' => 'Telefono Procuratore',
//            'CellulareProcuratore' => 'Cellulare Procuratore',
//        ];
//    }
   
    
    
    public function validateDenominazione($attribute, $params) 
    {
        if (($this->RegimeGiuridico_id>1) && empty($this->Denominazione)) {
            $this->addError($attribute, 'Denominazione deve essere specificata.');
        }
//        if (empty($this->listing_image_url) && empty($this->poster_image_url)) {
//            $this->addError($attribute, 'Please select one of "Listing" or "Poster Group".');
//        }
    }
    
    
    public function validateCognome() 
    {
        if (($this->RegimeGiuridico_id==1) && empty($this->Cognome)) {
            $this->addError('Cognome', 'Cognome deve essere specificato.');
        }
    }
    
    public function validateNome() 
    {
        if (($this->RegimeGiuridico_id==1) && empty($this->Nome)) {
            $this->addError('Nome', 'Nome deve essere specificato.');
        }
    }
    
    
    
    
    	public function getNomeCompleto()
    {
     if ($this->RegimeGiuridico_id==1) {
         if (isset($this->DataNascita)) {
        return $this->Cognome. ' ' .$this->Nome. ' ('.Yii::$app->formatter->asDate($this->DataNascita, 'd-M-Y').')'; 
         } else {
         return $this->Cognome. ' ' .$this->Nome; 
         }
     } else {
        return $this->Denominazione;
            }
    }
	
    public function getEdilizia1()
    {
         return $this->hasOne(Edilizia::className(), [ 'committente_id'=>'committenti_id' ]);

    }

    
    public function getSismica()
    {
         return $this->hasOne(Sismica::className(), [ 'committente_id'=>'committenti_id' ]);

    }

    public function getCdu()
    {
         return $this->hasOne(Cdu::className(), [ 'idrich'=>'committenti_id' ]);

    }
    
    
    public function getNomeBreve()
    {
     if ($this->RegimeGiuridico_id==0) {
        return $this->Cognome. ' ' .$this->Nome; 
     } else {
        return $this->Denominazione;
            }
    }
	
   
   public function getSoggetti()
    {
        return $this->hasOne(SoggettiEdilizia::className(), ['committenti_id' => 'committenti_id']);
     
    } 
    
    
    
    
}
