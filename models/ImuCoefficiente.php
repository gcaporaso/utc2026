<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Coefficienti catastali per il calcolo IMU (art. 13, DL 201/2011).
 *
 * @property int    $id
 * @property string $categoria
 * @property int    $coefficiente
 * @property string $descrizione
 */
class ImuCoefficiente extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'imu_coefficienti';
    }

    public function rules(): array
    {
        return [
            [['categoria', 'coefficiente'], 'required'],
            ['categoria', 'string', 'max' => 10],
            ['coefficiente', 'integer', 'min' => 1],
            ['descrizione', 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'categoria'    => 'Categoria catastale',
            'coefficiente' => 'Coefficiente',
            'descrizione'  => 'Descrizione',
        ];
    }

    /**
     * Restituisce il coefficiente per una categoria catastale.
     * Gestisce varianti con e senza slash (es. "A10" = "A/10").
     */
    public static function getCoeffForCategoria(string $cat): int
    {
        $cat = strtoupper(trim(str_replace([' ', '.'], '', $cat)));

        // Normalizza: "A10"→"A/10", "C02"→"C/02"→"C/2", "D5"→"D/5"
        $norm = preg_replace('/^([A-Z]+)(\d+)$/', '$1/$2', $cat);
        $norm = preg_replace('/\/0+(\d)/', '/$1', $norm); // "C/02"→"C/2"

        // 1) Cerca match con stringa originale
        $row = static::findOne(['categoria' => $cat]);
        if ($row) { return (int)$row->coefficiente; }

        // 2) Cerca con forma normalizzata (es. "A10" → "A/10")
        if ($norm !== $cat) {
            $row = static::findOne(['categoria' => $norm]);
            if ($row) { return (int)$row->coefficiente; }
        }

        // 3) Fallback sul gruppo principale (es. "A/5" → "A")
        //    MA non per categorie con coefficiente specifico (A/10, D/5)
        if (!in_array($norm, ['A/10', 'D/5'], true)) {
            $gruppo = preg_replace('/\/.*$/', '', $norm); // "A/3" → "A", "C/1" → "C"
            $row    = static::findOne(['categoria' => $gruppo]);
            if ($row) { return (int)$row->coefficiente; }
        }

        return 160; // default abitazioni
    }
}
