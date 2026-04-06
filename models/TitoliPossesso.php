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
class TitoliPossesso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'titoli_possesso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
                
        return [
            [['descrizione'], 'safe'],
        ];
    }
   
    public function getEdilizia1()
    {
         return $this->hasOne(Edilizia::className(), [ 'committente_id'=>'committenti_id' ]);

    }

    
    public function getSismica()
    {
         return $this->hasOne(Sismica::className(), [ 'committente_id'=>'committenti_id' ]);

    }

     public function getSoggetti()
    {
         return $this->hasOne(SoggettiEdilizia::className(), [ 'titolo_id'=>'idtitoli_possesso' ]);

    }
    
    
}
