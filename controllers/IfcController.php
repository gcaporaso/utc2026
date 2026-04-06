<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\httpclient\Client;

class IfcController extends Controller
{
    private $apiBaseUrl = 'http://ifcapi.miosito.it';   // il dominio/proxy configurato in Apache

    /**
     * Test chiamata API di root
     */
    public function actionPing()
    {
        $client = new Client(['baseUrl' => $this->apiBaseUrl]);
        $response = $client->get('/')->send();

        if ($response->isOk) {
            return $this->asJson($response->data);
        }

        return $this->asJson(['error' => 'API non raggiungibili']);
    }

    /**
     * Conta le entità IfcBuildingElement in un file IFC
     */
    public function actionCountEntities($filePath)
    {
        $client = new Client(['baseUrl' => $this->apiBaseUrl]);
        $response = $client->post('count_entities', [
            'ifc_path' => $filePath,   // percorso sul server accessibile da Hug
        ])->send();

        if ($response->isOk) {
            return $this->asJson($response->data);
        }

        return $this->asJson(['error' => 'Errore chiamata API']);
    }

    /**
     * Restituisce la lista dei muri in un file IFC
     */
    public function actionListWalls($filePath)
    {
        $client = new Client(['baseUrl' => $this->apiBaseUrl]);
        $response = $client->post('list_walls', [
            'ifc_path' => $filePath,
        ])->send();

        if ($response->isOk) {
            return $this->asJson($response->data);
        }

        return $this->asJson(['error' => 'Errore chiamata API']);
    }
}
