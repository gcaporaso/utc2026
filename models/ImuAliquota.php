<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Aliquote IMU deliberate dal Comune.
 *
 * @property int    $id
 * @property int    $anno
 * @property string $tipo
 * @property string $descrizione
 * @property float  $aliquota
 * @property float  $detrazione
 * @property int    $attiva
 * @property string $note
 */
class ImuAliquota extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'imu_aliquote';
    }

    public function rules(): array
    {
        return [
            [['anno', 'tipo', 'descrizione', 'aliquota'], 'required'],
            ['anno', 'integer', 'min' => 2000, 'max' => 2100],
            ['tipo', 'string', 'max' => 60],
            ['descrizione', 'string', 'max' => 255],
            ['aliquota', 'number', 'min' => 0, 'max' => 1],
            ['detrazione', 'number', 'min' => 0],
            ['attiva', 'integer'],
            ['note', 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'anno'        => 'Anno',
            'tipo'        => 'Tipo immobile',
            'descrizione' => 'Descrizione',
            'aliquota'    => 'Aliquota (decimale, es. 0.0076)',
            'detrazione'  => 'Detrazione annuale (€)',
            'attiva'      => 'Attiva',
            'note'        => 'Note',
        ];
    }

    /** Etichette human-friendly per i tipi — allineate al dropdown "Tipo utilizzo" del calcolo */
    public static function tipiLabels(): array
    {
        return [
            'abpr_std'             => 'Abitazione principale e assimilate (Cat. da A2 ad A7)',
            'pertinenza_std'       => 'Pertinenze Abitazione Principale (C/2, C/6, C/7)',
            'assimilata_anziani'   => 'Assimilata ad Abitazione Principale - posseduta da anziani o disabili',
            'abpr_lusso'           => 'Abitazione principale di pregio (Cat. A/1, A/8, A/9)',
            'pertinenza_lusso'     => 'Pertinenze Abitazione principale di pregio (C/2, C/6, C/7)',
            'altra_abitazione'     => 'Altre abitazioni - immobili Cat. A (tranne A/10)',
            'a10_uffici'           => 'Cat. A/10 - Uffici e studi privati',
            'c1_negozi'            => 'Cat. C/1 - Negozi e botteghe',
            'c2_magazzini'         => 'Cat. C/2 - Magazzini e locali di deposito',
            'c3_laboratori'        => 'Cat. C/3 - Laboratori per arti e mestieri',
            'b_c4_c5'              => 'Cat. B, C/4, C/5 - Fabbricati comuni',
            'c6_c7_stalle'         => 'Cat. C/6, C/7 - Stalle, scuderie, rimesse, autorimesse - Tettoie',
            'cat_d'                => 'Cat. D, tranne D/5 e D/10 - Immobili industriali e commerciali',
            'd5_credito'           => 'Cat. D/5 - Istituti di credito ed assicurazioni',
            'd10_rurale'           => 'Fabbricati rurali ad uso strumentale all\'attività agricola (D/10)',
            'rurale_abc'           => 'Fabbricati rurali ad uso strumentale all\'attività agricola (Cat. A, C/2, C/6, C/7)',
            'area_fabbricabile'    => 'Aree fabbricabili',
            'peep'                 => 'Aree PEEP - Piano di Edilizia Economica e Popolare',
            'comodato50'           => 'Abitazione concessa in comodato gratuito (tranne A/1, A/8, A/9) - riduzione 50% base imponibile',
            'comodato_noriduziome' => 'Abitazione in comodato gratuito senza riduzione imponibile',
            'iacp'                 => 'Abitazioni assegnate dagli Istituti Autonomi Case Popolari (ex IACP/ARES/ALER)',
            'terreno_agricolo'     => 'Terreni agricoli',
        ];
    }
}
