<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Una riga di variazione catastale per soggetto×immobile×atto.
 *
 * @property int    $id
 * @property int    $fornitura_id
 * @property string $anno_mese
 * @property int    $numero_nota
 * @property int    $anno_nota
 * @property string $data_presentazione
 * @property string $data_validita_atto
 * @property int    $esito_nota           0=registrata 1=parziale 2=non registrata
 * @property string $codice_fiscale
 * @property string $cognome
 * @property string $nome
 * @property string $tipo_variazione      'A'=acquisizione 'C'=cessione
 * @property string $codice_diritto
 * @property string $quota_numeratore
 * @property string $quota_denominatore
 * @property string $tipologia_immobile   'F'=fabbricato 'T'=terreno
 * @property string $foglio
 * @property string $numero
 * @property string $subalterno
 * @property string $id_catastale_immobile
 * @property string $categoria
 * @property string $classe
 * @property int    $superficie
 * @property float  $rendita
 * @property string $indirizzo
 * @property float  $dominicale
 * @property float  $agrario
 */
class IciVariazione extends ActiveRecord
{
    public static function tableName(): string { return 'ici_variazioni'; }

    public function rules(): array
    {
        return [
            [['fornitura_id', 'codice_fiscale', 'tipo_variazione', 'foglio', 'numero'], 'required'],
            [['numero_nota', 'anno_nota', 'esito_nota', 'fornitura_id', 'superficie'], 'integer'],
            [['rendita', 'dominicale', 'agrario'], 'number'],
            [['codice_fiscale', 'foglio', 'numero', 'subalterno', 'codice_diritto'], 'string', 'max' => 20],
            [['cognome', 'nome', 'indirizzo'], 'string', 'max' => 100],
            [['categoria', 'classe', 'tipologia_immobile', 'tipo_variazione'], 'string', 'max' => 10],
            [['data_presentazione', 'data_validita_atto'], 'safe'],
        ];
    }

    /**
     * Calcola i mesi IMU suggeriti in base alla data e al tipo variazione.
     * Regola: se acquisizione ≤15 del mese → conta dal mese stesso; ≥16 → dal mese dopo.
     * Per cessione: ≥16 → conta il mese stesso; ≤15 → non conta il mese.
     */
    public function mesiSuggeriti(): int
    {
        if (!$this->data_presentazione) return 0;
        $d   = new \DateTime($this->data_presentazione);
        $m   = (int)$d->format('n');
        $day = (int)$d->format('j');

        if ($this->tipo_variazione === 'A') {
            return $day <= 15 ? max(0, 13 - $m) : max(0, 12 - $m);
        } else {
            return $day >= 16 ? $m : max(0, $m - 1);
        }
    }

    /** Codici diritto rilevanti per IMU (proprietà e diritti reali di godimento). */
    public static function descrizioniDiritto(): array
    {
        return [
            '1'  => 'Proprietà', '1s' => 'Proprietà superficiaria', '1t' => 'Proprietà per area',
            '2'  => 'Nuda proprietà', '2s' => 'Nuda proprietà superficiaria',
            '3'  => 'Abitazione', '3s' => 'Abitazione su prop. superficiaria',
            '4'  => 'Diritto del concedente', '5' => 'Diritto dell\'enfiteuta',
            '6'  => 'Superficie', '7' => 'Uso', '8' => 'Usufrutto',
            '8a' => 'Usufrutto con accrescimento', '8e' => 'Usufrutto su enfiteusi',
            '8s' => 'Usufrutto su prop. superficiaria',
        ];
    }
}
