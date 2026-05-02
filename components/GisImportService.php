<?php

namespace app\components;

use Yii;
use app\models\GisLayer;
use Shapefile\ShapefileReader;

class GisImportService
{
    // CRS comuni in Italia
    public static function crsOptions()
    {
        return [
            4326  => 'WGS84 (EPSG:4326)',
            7794  => 'RDN2008 / Italy zone (EPSG:7794)',
            32632 => 'UTM32N WGS84 (EPSG:32632)',
            32633 => 'UTM33N WGS84 (EPSG:32633)',
            3003  => 'Gauss-Boaga Ovest (EPSG:3003)',
            3004  => 'Gauss-Boaga Est (EPSG:3004)',
            25832 => 'ETRS89 UTM32N (EPSG:25832)',
            25833 => 'ETRS89 UTM33N (EPSG:25833)',
            6708  => 'RDN2008 geografico (EPSG:6708)',
        ];
    }

    /**
     * Importa un file GeoJSON nel layer specificato.
     * Restituisce il numero di feature inserite.
     */
    public function importGeoJson(string $filePath, GisLayer $layer, int $sourceSrid = 4326): int
    {
        $json = file_get_contents($filePath);
        $data = json_decode($json, true);
        if (!$data || !isset($data['type'])) {
            throw new \RuntimeException('File GeoJSON non valido.');
        }

        $features = [];
        if ($data['type'] === 'FeatureCollection') {
            $features = $data['features'] ?? [];
        } elseif ($data['type'] === 'Feature') {
            $features = [$data];
        } else {
            $features = [['type' => 'Feature', 'geometry' => $data, 'properties' => []]];
        }

        $layer->tipo_geometria = $this->detectGeomType($features);
        if (!$layer->save()) {
            throw new \RuntimeException('Impossibile salvare il layer: ' . implode(', ', $layer->getFirstErrors()));
        }

        $count = 0;
        $db = Yii::$app->db;
        foreach ($features as $f) {
            $geomJson = $this->normalizeGeomJson(json_encode($f['geometry']));
            $props    = json_encode($f['properties'] ?? []);
            $sql = $sourceSrid === 4326
                ? "INSERT INTO gis_features (layer_id, geometria, proprieta)
                   VALUES (:lid, ST_GeomFromGeoJSON(:geom), :props)"
                : "INSERT INTO gis_features (layer_id, geometria, proprieta)
                   VALUES (:lid, ST_Transform(ST_GeomFromGeoJSON(:geom, 1, :srid), 4326), :props)";

            $params = [':lid' => $layer->id, ':geom' => $geomJson, ':props' => $props];
            if ($sourceSrid !== 4326) {
                $params[':srid'] = $sourceSrid;
            }
            $db->createCommand($sql, $params)->execute();
            $count++;
        }

        return $count;
    }

    /**
     * Importa uno shapefile (file .shp o directory con .shp+.dbf+.prj).
     * Restituisce il numero di feature inserite.
     */
    public function importShapefile(string $shpPath, GisLayer $layer, int $sourceSrid = 4326): int
    {
        // Prima passata: leggi tutte le geometrie in memoria per rilevare il tipo
        $reader  = new ShapefileReader($shpPath);
        $records = [];
        while ($geom = $reader->fetchRecord()) {
            if (!$geom->isDeleted()) {
                $records[] = $geom;
            }
        }

        if (empty($records)) {
            throw new \RuntimeException('Shapefile vuoto o senza geometrie valide.');
        }

        // Rileva tipo dalla prima geometria e salva il layer per ottenere l'id
        $firstJson = $this->normalizeGeomJson($records[0]->getGeoJSON(false, false));
        $layer->tipo_geometria = json_decode($firstJson, true)['type'] ?? 'Geometry';
        if (!$layer->save()) {
            throw new \RuntimeException('Impossibile salvare il layer: ' . implode(', ', $layer->getFirstErrors()));
        }

        $count = 0;
        $db    = Yii::$app->db;
        foreach ($records as $geom) {
            $geomJson = $this->normalizeGeomJson($geom->getGeoJSON(false, false));
            if (!$geomJson) continue;

            $props = json_encode($geom->getDataArray());
            $sql = $sourceSrid === 4326
                ? "INSERT INTO gis_features (layer_id, geometria, proprieta)
                   VALUES (:lid, ST_GeomFromGeoJSON(:geom), :props)"
                : "INSERT INTO gis_features (layer_id, geometria, proprieta)
                   VALUES (:lid, ST_Transform(ST_GeomFromGeoJSON(:geom, 1, :srid), 4326), :props)";

            $params = [':lid' => $layer->id, ':geom' => $geomJson, ':props' => $props];
            if ($sourceSrid !== 4326) {
                $params[':srid'] = $sourceSrid;
            }
            $db->createCommand($sql, $params)->execute();
            $count++;
        }

        return $count;
    }

    /**
     * Legge il SRID dal file .prj dello shapefile, se presente.
     * Restituisce null se non riconosciuto.
     */
    public function sridFromPrj(string $shpPath): ?int
    {
        $prjPath = preg_replace('/\.shp$/i', '.prj', $shpPath);
        if (!file_exists($prjPath)) return null;

        $prj = file_get_contents($prjPath);
        // Cerca EPSG nel WKT
        if (preg_match('/AUTHORITY\["EPSG","(\d+)"\]\s*\]?\s*$/', $prj, $m)) {
            return (int)$m[1];
        }
        // Gauss-Boaga
        if (stripos($prj, 'Monte_Mario') !== false) {
            return stripos($prj, 'Ovest') !== false ? 3003 : 3004;
        }
        return null;
    }

    private function detectGeomType(array $features): string
    {
        foreach ($features as $f) {
            $t = $f['geometry']['type'] ?? 'Geometry';
            return preg_replace('/[ZM]+$/i', '', $t);
        }
        return 'Geometry';
    }

    /**
     * Normalizza un GeoJSON di geometria per MySQL ST_GeomFromGeoJSON:
     * - rimuove i suffissi Z/M/ZM/ZMZ dal "type" (es. PolygonM → Polygon)
     * - riduce le coordinate a 2D [x, y]
     */
    private function normalizeGeomJson(string $geomJson): string
    {
        $data = json_decode($geomJson, true);
        if (!$data) return $geomJson;

        $data['type'] = preg_replace('/[ZM]+$/i', '', $data['type']);

        if (isset($data['coordinates'])) {
            $data['coordinates'] = $this->trimCoords($data['coordinates']);
        }
        if (isset($data['geometries'])) {
            foreach ($data['geometries'] as &$g) {
                $g['type'] = preg_replace('/[ZM]+$/i', '', $g['type']);
                if (isset($g['coordinates'])) {
                    $g['coordinates'] = $this->trimCoords($g['coordinates']);
                }
            }
        }

        return json_encode($data);
    }

    private function trimCoords(array $coords): array
    {
        if (!isset($coords[0])) return $coords;
        if (is_numeric($coords[0])) {
            // singolo punto: [x, y, z?, m?] → [x, y]
            return [$coords[0], $coords[1]];
        }
        return array_map([$this, 'trimCoords'], $coords);
    }
}
