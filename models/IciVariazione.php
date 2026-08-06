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
     * Calcola i mesi IMU suggeriti per l'anno $annoCalc.
     *
     * Usa data_validita_atto come data giuridicamente efficace (es. apertura successione,
     * data dell'atto notarile), con fallback a data_presentazione se assente.
     *
     * Se la data dell'atto è in un anno precedente ad $annoCalc → acquisizione già in essere
     * dall'anno prima: mesi = 12 (pieno anno). Se è in un anno successivo → mesi = 0.
     * Se è nello stesso anno: regola giorno ≤15 / ≥16.
     */
    public function mesiSuggeriti(int $annoCalc = 0): int
    {
        // Preferisce data_validita_atto (data giuridica dell'atto) a data_presentazione (trascrizione)
        $dataRef = ($this->data_validita_atto && $this->data_validita_atto > '0000-00-00')
            ? $this->data_validita_atto
            : $this->data_presentazione;
        if (!$dataRef) return 0;

        $d    = new \DateTime($dataRef);
        $anno = (int)$d->format('Y');
        $m    = (int)$d->format('n');
        $day  = (int)$d->format('j');

        if ($annoCalc > 0 && $anno !== $annoCalc) {
            if ($this->tipo_variazione === 'A') {
                return $anno < $annoCalc ? 12 : 0;
            } else {
                return $anno < $annoCalc ? 0 : 12;
            }
        }

        if ($this->tipo_variazione === 'A') {
            return $day <= 15 ? max(0, 13 - $m) : max(0, 12 - $m);
        } else {
            return $day >= 16 ? $m : max(0, $m - 1);
        }
    }

    /**
     * Restituisce la quota come frazione semplificata (es. "1/2", "1/3").
     * I valori XML sono in millesimi: ratio = numeratore/denominatore (es. 500 = 500‰ = 1/2).
     */
    public static function quotaFrazione(?int $num, ?int $den): string
    {
        if (!$num || !$den || $den === 0) return '—';
        $ratio = $num / $den;
        // ratio > 1 → già in millesimi (es. 500); ratio ≤ 1 → frazione propria (es. 0.5)
        $millesimi = $ratio > 1 ? (int)round($ratio) : (int)round($ratio * 1000);
        if ($millesimi <= 0 || $millesimi > 1000) return $num . '/' . $den;
        $a = $millesimi; $b = 1000;
        while ($b) { [$a, $b] = [$b, $a % $b]; }
        return ($millesimi / $a) . '/' . (1000 / $a);
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
