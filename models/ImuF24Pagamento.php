<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Pagamenti IMU rilevati dalle forniture SOGEI F24.
 *
 * @property int    $id
 * @property int    $anno_riferimento
 * @property string $codice_fiscale
 * @property string $codice_fiscale_orig
 * @property string $codice_tributo
 * @property string $tipo_imposta
 * @property string $data_riscossione
 * @property string $data_fornitura
 * @property string $data_ripartizione
 * @property float  $importo_debito
 * @property float  $importo_credito
 * @property float  $detrazione
 * @property int    $acconto
 * @property int    $saldo
 * @property int    $ravvedimento
 * @property int    $immobili_variati
 * @property int    $num_fabbricati
 * @property string $progressivo_delega
 * @property int    $progressivo_riga
 * @property string $codice_ente_comunale
 * @property string $denominazione
 * @property string $nome_contribuente
 * @property string $sesso
 * @property string $data_nascita
 * @property string $comune_nascita
 * @property string $provincia_nascita
 * @property string $file_origine
 * @property string $importato_il
 */
class ImuF24Pagamento extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'imu_f24_pagamenti';
    }

    /** Codici tributo IMU con descrizione */
    public static function codiciTributo(): array
    {
        return [
            '3912' => 'Abitazione principale e pertinenze',
            '3913' => 'Fabbricati rurali ad uso strumentale',
            '3914' => 'Terreni',
            '3916' => 'Aree fabbricabili',
            '3918' => 'Altri fabbricati',
            '3925' => 'Immobili produttivi cat. D (quota Stato)',
            '3930' => 'Immobili produttivi cat. D (quota Comune)',
        ];
    }

    /**
     * Somma acconto effettivamente pagato per un CF e un anno, solo record IMU.
     * Restituisce array ['acconto' => float, 'saldo' => float, 'totale' => float].
     */
    public static function riepilogoCf(string $cf, int $anno): array
    {
        $cf = strtoupper(trim($cf));
        $rows = static::find()
            ->where(['codice_fiscale' => $cf, 'anno_riferimento' => $anno, 'tipo_imposta' => 'I'])
            ->andWhere(['ravvedimento' => 0])
            ->all();

        $totAcc  = 0.0;
        $totSaldo = 0.0;
        foreach ($rows as $r) {
            $netto = $r->importo_debito - $r->importo_credito;
            if ($r->acconto && !$r->saldo) {
                $totAcc += $netto;
            } elseif ($r->saldo && !$r->acconto) {
                $totSaldo += $netto;
            }
        }
        return [
            'acconto' => round($totAcc, 2),
            'saldo'   => round($totSaldo, 2),
            'totale'  => round($totAcc + $totSaldo, 2),
            'righe'   => count($rows),
        ];
    }

    public function rules(): array
    {
        return [
            [['anno_riferimento', 'codice_fiscale', 'codice_tributo'], 'required'],
            ['codice_fiscale', 'string', 'max' => 16],
            ['codice_tributo', 'string', 'max' => 4],
            ['tipo_imposta', 'string', 'max' => 1],
            [['importo_debito', 'importo_credito', 'detrazione'], 'number', 'min' => 0],
            [['acconto', 'saldo', 'ravvedimento', 'immobili_variati'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'anno_riferimento'   => 'Anno',
            'codice_fiscale'     => 'Codice Fiscale',
            'codice_tributo'     => 'Codice Tributo',
            'tipo_imposta'       => 'Tipo Imposta',
            'data_riscossione'   => 'Data Riscossione',
            'importo_debito'     => 'Importo Pagato',
            'importo_credito'    => 'Importo a Credito',
            'detrazione'         => 'Detrazione',
            'acconto'            => 'Acconto',
            'saldo'              => 'Saldo',
            'ravvedimento'       => 'Ravvedimento',
            'denominazione'      => 'Denominazione',
            'nome_contribuente'  => 'Nome',
            'file_origine'       => 'File',
            'importato_il'       => 'Importato il',
        ];
    }
}
