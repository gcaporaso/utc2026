<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\GisProgetto;
use app\models\GisLayer;
use app\components\GisImportService;

class ProgettiGisController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete'        => ['POST'],
                    'delete-layer'  => ['POST'],
                ],
            ],
        ];
    }

    // ── Elenco progetti ──────────────────────────────────────────────────────
    public function actionIndex()
    {
        $dp = new ActiveDataProvider([
            'query'      => GisProgetto::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);
        return $this->render('index', ['dataProvider' => $dp]);
    }

    // ── Nuovo progetto ───────────────────────────────────────────────────────
    public function actionCreate()
    {
        $model = new GisProgetto();
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    // ── Dettaglio progetto + lista layer ─────────────────────────────────────
    public function actionView($id)
    {
        $progetto = $this->findProgetto($id);
        return $this->render('view', ['progetto' => $progetto]);
    }

    // ── Elimina progetto ─────────────────────────────────────────────────────
    public function actionDelete($id)
    {
        $this->findProgetto($id)->delete();
        return $this->redirect(['index']);
    }

    // ── Upload layer (GeoJSON o Shapefile zip) ───────────────────────────────
    public function actionUpload($id)
    {
        $progetto = $this->findProgetto($id);
        $svc      = new GisImportService();

        if (Yii::$app->request->isPost) {
            $file       = UploadedFile::getInstanceByName('gis_file');
            $nome       = Yii::$app->request->post('nome_layer', $file->baseName);
            $sridManual = (int)Yii::$app->request->post('srid_manual', 0);
            $srid       = $sridManual > 0 ? $sridManual : (int)Yii::$app->request->post('srid', 4326);
            $ext   = strtolower($file->extension);

            if (!$file) {
                Yii::$app->session->setFlash('error', 'Nessun file selezionato.');
                return $this->redirect(['view', 'id' => $id]);
            }

            $tmpDir  = Yii::getAlias('@runtime/gis_upload');
            if (!is_dir($tmpDir)) mkdir($tmpDir, 0775, true);

            $tmpPath = $tmpDir . '/' . uniqid('gis_') . '.' . $ext;
            $file->saveAs($tmpPath);

            $layer = new GisLayer([
                'progetto_id'    => $progetto->id,
                'nome'           => $nome,
                'tipo_geometria' => 'Geometry',
                'srid'           => 4326,
            ]);

            try {
                if ($ext === 'geojson' || $ext === 'json') {
                    $count = $svc->importGeoJson($tmpPath, $layer, $srid);
                } elseif ($ext === 'zip') {
                    $count = $this->importShapefileZip($tmpPath, $tmpDir, $layer, $srid, $svc);
                } else {
                    throw new \RuntimeException('Formato non supportato. Usa .geojson o .zip (shapefile).');
                }
                Yii::$app->session->setFlash('success', "Importate {$count} feature nel layer «{$nome}».");
            } catch (\Exception $e) {
                $layer->delete();
                Yii::$app->session->setFlash('error', 'Errore importazione: ' . $e->getMessage());
            } finally {
                @unlink($tmpPath);
            }

            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('upload', [
            'progetto'   => $progetto,
            'crsOptions' => GisImportService::crsOptions(),
        ]);
    }

    // ── Elimina layer ────────────────────────────────────────────────────────
    public function actionDeleteLayer($id)
    {
        $layer = GisLayer::findOne($id);
        if ($layer) {
            $progettoId = $layer->progetto_id;
            $layer->delete();
            return $this->redirect(['view', 'id' => $progettoId]);
        }
        throw new NotFoundHttpException();
    }

    // ── API GeoJSON per Leaflet ──────────────────────────────────────────────
    public function actionGeoJson($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $progetto = $this->findProgetto($id);

        $features = [];
        foreach ($progetto->layers as $layer) {
            $rows = Yii::$app->db->createCommand(
                'SELECT id, ST_AsGeoJSON(geometria) AS geom, proprieta FROM gis_features WHERE layer_id = :lid',
                [':lid' => $layer->id]
            )->queryAll();

            foreach ($rows as $row) {
                $props = json_decode($row['proprieta'], true) ?? [];
                $props['_layer']  = $layer->nome;
                $props['_tipo']   = $layer->tipo_geometria;
                $features[] = [
                    'type'       => 'Feature',
                    'geometry'   => json_decode($row['geom'], true),
                    'properties' => $props,
                ];
            }
        }

        return [
            'type'     => 'FeatureCollection',
            'features' => $features,
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────
    private function findProgetto($id): GisProgetto
    {
        $m = GisProgetto::findOne($id);
        if (!$m) throw new NotFoundHttpException('Progetto non trovato.');
        return $m;
    }

    private function importShapefileZip(string $zipPath, string $tmpDir, GisLayer $layer, int $srid, GisImportService $svc): int
    {
        $extractDir = $tmpDir . '/shp_' . uniqid();
        mkdir($extractDir, 0775, true);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('Impossibile aprire il file ZIP.');
        }
        $zip->extractTo($extractDir);
        $zip->close();

        $shpFiles = glob($extractDir . '/*.shp');
        if (empty($shpFiles)) {
            throw new \RuntimeException('Nessun file .shp trovato nel ZIP.');
        }

        $shpPath = $shpFiles[0];

        // Rileva SRID dal .prj se non specificato dall'utente
        if ($srid === 4326) {
            $detectedSrid = $svc->sridFromPrj($shpPath);
            if ($detectedSrid) $srid = $detectedSrid;
        }

        $layer->srid = $srid;
        $count = $svc->importShapefile($shpPath, $layer, $srid);

        // Pulizia
        array_map('unlink', glob($extractDir . '/*'));
        rmdir($extractDir);

        return $count;
    }
}
