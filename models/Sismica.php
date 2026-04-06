<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sismica".
 *
 * @property int $sismica_id
 * @property int|null $pratica_id
 * @property string $DataProtocollo
 * @property int $Protocollo
 * @property string|null $DescrizioneLavori
 * @property int|null $committenti_id
 * @property int $TipoDenuncia
 * @property int $StrutturaPortante
 * @property string|null $PROG_ARCH_ID
 * @property string|null $DD_LL_ARCH_ID
 * @property string|null $PROG_STR_ID
 * @property string|null $DIR_LAV_STR_ID
 * @property string|null $IMPRESA_ID
 * @property string|null $COLLAUDATORE_ID
 * @property int|null $IstruttoreID
 * @property string|null $ISTRUTTORE
 * @property int|null $NumeroAUTORIZZAZIONE
 * @property string|null $DataAUTORIZZAZIONE
 * @property float|null $ImportoContributo
 * @property string|null $DataVersamentoContributo
 * @property string|null $PAGATO_COMMISSIONE
 * @property int $TrasmissioneGenioCivile
 * @property int $PubblicazioneALBO
 * @property int $Ritiro
 * @property string|null $Inizio_Lavori
 * @property string|null $Fine_Lavori
 * @property string|null $Data_Strutture_Ultimate
 * @property string|null $Data_Collaudo
 * @property int $TipoTitolo
 * @property int $TipoCommittente
 * @property int $TipoProcedimento
 * @property int $Variante
 * @property int $Sopraelevazione
 * @property int $Ampliamento
 * @property int $Integrazione
 * @property int $CatastoTipo
 * @property string|null $CatastoFoglio
 * @property string|null $CatastoParticelle
 * @property string|null $CatastoSub
 * @property string|null $StrutturaAltro
 * @property int $ClassificazioneSismica
 * @property float|null $AccelerazioneAg
 * @property int $AbitatoConsolidare
 * @property int $InteresseStatale
 * @property int $InteresseRegionale
 * @property int $Articolo65
 * @property int|null $GeologoID
 * @property string|null $DestinazioneDuso
 */
class Sismica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sismica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DataProtocollo', 'Protocollo','committenti_id','TipoTitolo','TipoProcedimento','TipoDenuncia','DescrizioneLavori'], 'required'],
            [['sismica_id', 'pratica_id', 'Protocollo', 'committenti_id', 'TipoDenuncia', 'cemento_armato', 'IstruttoreID', 'NumeroAUTORIZZAZIONE', 'TrasmissioneGenioCivile', 
                'PubblicazioneALBO', 'Ritiro', 'TipoTitolo', 'TipoCommittente', 'TipoProcedimento', 'Variante', 'Sopraelevazione', 'Ampliamento', 'Integrazione', 'CatastoTipo', 
                'ClassificazioneSismica', 'AbitatoConsolidare', 'InteresseStatale', 'InteresseRegionale', 'Articolo65', 'GeologoID','NumeroProtocolloAutorizzazione',
                'muratura_ordinaria','muratura_armata','cemento_precompresso','struttura_metallica','struttura_legno',
                'PAGATO_COMMISSIONE','ImportoPagato'], 'integer'],
            [['DataProtocollo', 'DataAUTORIZZAZIONE', 'DataVersamentoContributo', 'Inizio_Lavori', 'Fine_Lavori', 'Data_Strutture_Ultimate', 'Data_Collaudo','titolo',
                'statoPratica'], 'safe'],
            [['ImportoContributo', 'AccelerazioneAg','ImportoPagato'], 'number'],
            [['DescrizioneLavori', 'PROG_ARCH_ID', 'DD_LL_ARCH_ID', 'PROG_STR_ID', 'DIR_LAV_STR_ID', 'IMPRESA_ID', 'COLLAUDATORE_ID'], 'string', 'max' => 255],
//            [['PAGATO_COMMISSIONE'], 'string', 'max' => 5],
            [['CatastoFoglio', 'CatastoParticelle', 'CatastoSub', 'StrutturaAltro', 'DestinazioneDuso','Ubicazione'], 'string', 'max' => 45],
            [['sismica_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sismica_id' => 'Sismica ID',
            'pratica_id' => 'Pratica ID',
            'DataProtocollo' => 'Data Protocollo',
            'Protocollo' => 'Protocollo',
            'DescrizioneLavori' => 'Descrizione Lavori',
            'committenti_id' => 'Committenti ID',
            'TipoDenuncia' => 'Tipo Denuncia',
            //'StrutturaPortante' => 'Struttura Portante',
            'PROG_ARCH_ID' => 'Prog Arch ID',
            'DD_LL_ARCH_ID' => 'Dd Ll Arch ID',
            'PROG_STR_ID' => 'Prog Str ID',
            'DIR_LAV_STR_ID' => 'Dir Lav Str ID',
            'IMPRESA_ID' => 'Impresa ID',
            'COLLAUDATORE_ID' => 'Collaudatore ID',
            'IstruttoreID' => 'Istruttore ID',
            'NumeroAUTORIZZAZIONE' => 'Numero Autorizzazione',
            'DataAUTORIZZAZIONE' => 'Data Autorizzazione',
            'ImportoContributo' => 'Importo Contributo',
            'DataVersamentoContributo' => 'Data Versamento Contributo',
            'PAGATO_COMMISSIONE' => 'Pagato Commissione',
            'TrasmissioneGenioCivile' => 'Trasmissione Genio Civile',
            'PubblicazioneALBO' => 'Pubblicazione Albo',
            'Ritiro' => 'Ritiro',
            'Inizio_Lavori' => 'Inizio Lavori',
            'Fine_Lavori' => 'Fine Lavori',
            'Data_Strutture_Ultimate' => 'Data Strutture Ultimate',
            'Data_Collaudo' => 'Data Collaudo',
            'TipoTitolo' => 'Tipo Titolo',
            'TipoCommittente' => 'Tipo Committente',
            'TipoProcedimento' => 'Tipo Procedimento',
            'Variante' => 'Variante',
            'Sopraelevazione' => 'Sopraelevazione',
            'Ampliamento' => 'Ampliamento',
            'Integrazione' => 'Integrazione',
            'CatastoTipo' => 'Catasto Tipo',
            'CatastoFoglio' => 'Catasto Foglio',
            'CatastoParticelle' => 'Catasto Particelle',
            'CatastoSub' => 'Catasto Sub',
            'StrutturaAltro' => 'Struttura Altro',
            'ClassificazioneSismica' => 'Classificazione Sismica',
            'AccelerazioneAg' => 'Accelerazione Ag',
            'AbitatoConsolidare' => 'Abitato Consolidare',
            'InteresseStatale' => 'Interesse Statale',
            'InteresseRegionale' => 'Interesse Regionale',
            'Articolo65' => 'Articolo65',
            'GeologoID' => 'Geologo ID',
            'DestinazioneDuso' => 'Destinazione Duso',
            'Ubicazione'=>'Ubicazione',
        ];
    }
    
     public function getRichiedente()
    {
        return $this->hasOne(Committenti::className(), [ 'committenti_id' => 'committenti_id']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
    
    
    public function getTitolo()
    {
        return $this->hasOne(TitoliSismica::className(), [ 'idtitoli_sismica' => 'TipoTitolo']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
    
    
//     public function getMateriale()
//    {
//        return $this->hasOne(MaterialeSismica::className(), [ 'materiale_id' => 'StrutturaPortante']);
//     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
//    }
//    
     public function getProcedimento()
    {
        return $this->hasOne(TipoProcedimentoSismica::className(), [ 'idtipo' => 'TipoProcedimento']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
  
    
    public function getCollaudatore()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'COLLAUDATORE_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
    
    public function getAllegati()
    {
        return $this->hasMany(AllegatiSismica::className(), [ 'idallegati' => 'sismica_id']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
    
     public function getStatoPratica(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasOne(StatoEdilizia::className(), ['idstato_edilizia' => 'StatoPratica']);
                    
    }
    
    public function getSoggetti()
    {
         return $this->hasMany(SoggettiSismica::className(), [ 'sismica_id'=>'sismica_id' ]);

    }
    
    
    public function getTecnici()
    {
        return $this->hasMany(Tecnici::className(), [ 'sismica_id' => 'sismica_id']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
    
    
}
