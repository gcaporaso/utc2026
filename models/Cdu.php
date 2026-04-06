<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "imprese".
 *
 * @property int $imprese_id
 * @property string|null $RAGIONE_SOCIALE
 * @property string|null $COGNOME
 * @property string|null $NOME
 * @property string|null $DATA_NASCITA
 * @property string|null $PROVINCIA_NASCITA
 * @property string|null $NOME_COMPOSTO
 * @property string|null $INDIRIZZO
 * @property string|null $COMUNE_RESIDENZA
 * @property string|null $PROVINCIA_RESIDENZA
 * @property string|null $CODICE_FISCALE
 * @property string|null $P.IVA
 * @property string|null $EMAIL
 * @property string|null $PEC
 * @property int $Cassa_Edile
 * @property string|null $INPS
 * @property string|null $INAIL
 * @property int $Telefono
 * @property int $Cellulare
 */
class Cdu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cdu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcdu'], 'safe'],
            [['NumeroProtocollo','DataProtocollo','idrich','foglio1','particelle1'], 'required'],
            [['sez1','sez2','foglio2','sez3','foglio3','sez4','foglio4'], 'string', 'max' => 45],
            [['particelle2','particelle3','particelle4'], 'string', 'max' => 255],
            [['tipodestinatario', 'esenzione'], 'required'],
            [['tipodestinatario', 'esenzione'], 'integer'],
            ['particelle1', 'match', 'pattern' => '/^\s*([1-9]{1}[0-9]{0,3}\s*[,]?\s*)+$/', 'message' => 'Devi inserire solo numeri separati da virgola'],
            ['particelle2', 'match', 'pattern' => '/^\s*([1-9]{1}[0-9]{0,3}\s*[,]?\s*)+$/', 'message' => 'Devi inserire solo numeri separati da virgola'],
            ['particelle3', 'match', 'pattern' => '/^\s*([1-9]{1}[0-9]{0,3}\s*[,]?\s*)+$/', 'message' => 'Devi inserire solo numeri separati da virgola'],
            ['particelle4', 'match', 'pattern' => '/^\s*([1-9]{1}[0-9]{0,3}\s*[,]?\s*)+$/', 'message' => 'Devi inserire solo numeri separati da virgola'],
            ['esenzione','safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcdu' => 'ID',
            'idrich' => 'ID Richiedente',
            'sez1' => 'Sezione',
            'sez2' => 'Sezione',
            'sez3' => 'Sezione',
            'sez4' => 'Sezione',
            'foglio1'=>'Foglio',
            'foglio2'=>'Secondo Foglio',
            'foglio3'=>'Terzo Foglio',
            'foglio4'=>'Quarto Foglio',
            'particelle1'=>'Particelle',
            'particelle2'=>'Particelle Secondo Foglio',
            'particelle3'=>'Particelle Terzo Foglio',
            'particelle4'=>'Particelle Quarto Foglio',
            'tipodestinatario' => 'Tipo Destinatario',
            'esenzione' => 'Esenzione',
            'NumeroProtocollo'=>'Numero Protocollo',
            'DataProtocollo'=>'Data Protocollo',
        ];
    }
    
     public function getRichiedente()
    {
        return $this->hasOne(Committenti::className(), [ 'committenti_id' => 'idrich']);
    }
    
    
    
    
    
}
