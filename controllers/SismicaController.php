<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Sismica;
use app\models\SearchSismica;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use kartik\dynagrid\models\DynaGridConfig;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use kartik\mpdf\Pdf;
use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Shared;
use PhpOffice\PhpWord\Settings;
use yii\web\UploadedFile;


class SismicaController extends \yii\web\Controller
{
    
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'allow'=>true,
                        'actions' => ['nuova','view','update','create','word','delete','autorizzazione','ripartocommissioni',
                                       'reportcontributi','allegati','cancellafile','soggetti','tecnici',
                                    'cancellatecnico','atti','documento'],
                        'roles' => ['RUP Pratiche Edilizie'],
                    ],
                    [
                        'allow'=>true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
                 'denyCallback' => function ($rule, $action){
                // everything else is denied
                    if(Yii::$app->request->referrer){
                        return $this->redirect(Yii::$app->request->referrer);
                    } else {
                        return $this->goHome();
                    }
                
                },
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
           ],  
        ];
    }
    
    
    
     /**
     * Visualizza la Homepage del modulo Edilizia.
     *
     * @return string
     */
    public function AggiornaImportoPagato()
    {
         // Questa query funziona 
    $epratiche= Sismica::find()->all();
    
    
    //return $this->render('test', ['html' =>json_encode($epratiche)]); 
    
        foreach ($epratiche as $pratica) {
            if (isset($pratica->ImportoContributo)) {
                $pratica->ImportoPagato=$pratica->ImportoContributo;
                $pratica->save(false);
            }
         };
        
     //return $this->render('test', ['html' =>json_encode($anni)]); 
        return true;
    }
    
    
    
    
    public function InserisciTecnico($idtecnico, $idruolo, $idsismica)
    {
        // ruolo_id','sismica_id','tecnici_id
        $tecnico = new \app\models\SismicaTecnici;
        $tecnico->sismica_id=$idsismica;
        $tecnico->ruolo_id=$idruolo;
        $tecnico->tecnici_id=$idtecnico;
        $tecnico->save(false);
        
    }
    
    
    
    
    
     /**
     * Visualizza la Homepage del modulo Edilizia.
     *
     * @return string
     */
    public function ImportaTecnici()
    {
         // Questa query funziona 
    $epratiche= Sismica::find()
            ->all();
    
    
    //return $this->render('test', ['html' =>json_encode($epratiche)]); 
    $n=0;
        foreach ($epratiche as $pratica) {
            if (isset($pratica->DIR_LAV_STR_ID)) {
                $ai = $this->InserisciTecnico($pratica->DIR_LAV_STR_ID, 4, $pratica->sismica_id);
                $n=$n+1;
            }
            if (isset($pratica->DD_LL_ARCH_ID)) {
                $ai = $this->InserisciTecnico($pratica->DD_LL_ARCH_ID, 3, $pratica->sismica_id);
                $n=$n+1;
            }
            if (isset($pratica->COLLAUDATORE_ID)) {
                $ai = $this->InserisciTecnico($pratica->COLLAUDATORE_ID, 6, $pratica->sismica_id);
                $n=$n+1;
            }
            if (isset($pratica->GeologoID)) {
                $ai = $this->InserisciTecnico($pratica->GeologoID, 5, $pratica->sismica_id);
                $n=$n+1;
            }
            if (isset($pratica->PROG_STR_ID)) {
                $ai = $this->InserisciTecnico($pratica->PROG_STR_ID, 2, $pratica->sismica_id);
                $n=$n+1;
            }
            if (isset($pratica->PROG_ARCH_ID)) {
                $ai = $this->InserisciTecnico($pratica->PROG_ARCH_ID, 1, $pratica->sismica_id);
                $n=$n+1;
            }
         };
        
     //return $this->render('test', ['html' =>json_encode($anni)]); 
        return $n;
    }
    
    
    
    
    
    public function actionIndex()
    {
        // Attivare per copiare ImportoContributo in ImportoPagato
        //$this->AggiornaImportoPagato();
        // Attivare per importare tecnici da Sismica
        // e inserirli nella tabella soggetti_sismica_tecnici
         //$tecnici = $this->ImportaTecnici();
         $this->layout = 'main';
	 $searchModel = new SearchSismica;
	 $model = new DynaGridConfig();
	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->sort->defaultOrder = ['DataProtocollo' => SORT_DESC];
         
         return $this->render('index', [
                        'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'model' => $model,
                        //'dateto'=>$date,
        ]);
    }

    
    
    
    
    
   /**
     * Creates a new Edilizia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sismica();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                //$model->Stato_Pratica_id=1;
                //$model->TitoloOneroso=0;
                //$model->DataProtocollo=date('Y-m-d',strtotime($model->DataProtocollo));
                $model->save();
                //return $this->redirect(['view', 'id' => $model->edilizia_id]);
                return $this->redirect(['index']);
            // all inputs are valid
            } else {
            // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }
        $model->scenario = 'insert';
        //$model->id_titolo=4;
        return $this->render('create', [
            'model' => $model,
        ]);
    }  
    
    
    
     /**
     * Signup new user
     * @return string
     */
    public function actionUpdate($id)
    {
    
	$model = Sismica::findOne($id);
            

     if ($model->load(Yii::$app->request->post())) {
         $model->AbitatoConsolidare=0;
         $model->PROG_ARCH_ID='';
         $model->PROG_STR_ID='';
         $model->DIR_LAV_STR_ID='';
         if ($model->validate()) {
            if ($model->save()) {
             echo Yii::$app->session->setFlash('success', "Pratica Aggiornata!");
             return $this->redirect(['index']);
           } else {
               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. La Pratica non è stata aggiornata!");
           }
         } else {
             $errors=$model->errors;
             echo Yii::$app->session->setFlash('error', "Errore di validazione!");
             //print_r($model->errors);
         }
    } 
    $model->scenario = 'update';
    return $this->render('update',['model' => $model]);

  }
    
   /**
     * Signup new user
     * @return string
     */
    public function actionDelete($id)
    {
        $model = Sismica::findOne($id); 
        $hoAllegati = $model->getAllegati()->count()>0;
        if ($hoAllegati) {
           Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Non posso cancellare la pratica perché contiene allegati!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2500,
                        'showConfirmButton'=>false,
                        'position'=>'center',
                    ]
            ]);
            return $this->redirect(['index']);
        }
        $hoSoggetti = $model->getSoggetti()->count()>0;
        if ($hoSoggetti) {
           Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Non posso cancellare la pratica perché contiene soggetti correlati!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2500,
                        'showConfirmButton'=>false,
                        'position'=>'center',
                    ]
            ]);
            return $this->redirect(['index']);
        }
        $hoTecnici = $model->getTecnici()->count()>0;
        if ($hoTecnici) {
           Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Non posso cancellare la pratica perché contiene tecnici correlati!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2500,
                        'showConfirmButton'=>false,
                        'position'=>'center',
                    ]
            ]);
            return $this->redirect(['index']);
        }
        
        $model->delete();
        return $this->redirect(['index']);
    } 
    
    
    
    
     /**
     * Visualizza un Lavoro User model.
     * @param integer $id
     * @return mixed
     */
    public function actionWord($idsismica)
    {
		//$this->idl=$id;
		//$this->layout = 'lay-lavori';
		$prsismica=Sismica::findOne($idsismica);
		//$user = User::find()->where(['name' => 'CeBe'])->one();
		//$natura = Cup_natura::find()->where(['Codice' => $lavori->CodiceNatura])->one();
		//$tipologia=Cup_tipologia::find()->where(['CodiceNatura' => $lavori->CodiceNatura,'CodiceTipologia' => $lavori->CodiceTipologia])->one()->DescrizioneTipologia;
        return $this->render('word', [
                'model' => $prsismica,
               'idsismica'=>$idsismica
        ]);
    }
    
    
    
    
    
    
    
    
    
     /**
     * Visualizza un Prospetto in formato PDF
     * contenente il report delle somme ancora da pagare 
     * alle commissioni sismiche
     * @param integer $id
     * @return mixed
     */
    public function actionReportcontributi()
    {
        $importi=Sismica::find()
                ->select('Year(DataProtocollo) AS anno, sum(ImportoContributo) as somma')
                ->groupBy('anno')
                //->orderBy(['(Year(DataProtocollo)'=>SORT_ASC])
                ->all();
       
	                      
     if (!isset($importi)) {
         Yii::$app->session->setFlash('error', "Si è verificato un errore! Non sono rieuscito a generare il Prospetto.");
        return $this->redirect(Yii::$app->request->referrer);
     }
     
    $content =  
'
<p><strong>PROSPETTO IMPORTI SISMICA' .'</strong></p>
<p>Riepilogo Contributo Incassato per Pratiche Sismiche :</p>
<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="64" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Anno</th>
    <th width="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Importo</th>
  </tr>';
//    $tipo=['Rata','Unica'];
    $riga='';
//    $formatter = \Yii::$app->formatter;

    foreach ($importi as $importo) {
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $importo['anno'] .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. '*' .'</td><tr>';
         $content = $content . $riga; 
     }
  
         
 $content=$content . ' 
<div><p></p></div>
<div><p></p></div>
<p>Campoli del Monte Taburno, li '. date("d-m-Y") . '</p>
<p>&nbsp;</p>';       
//$this->renderPartial('_reportView');
    
    // setup kartik\mpdf\Pdf component
    $pdf = new Pdf([
        'filename'=>'Prospetto_Oneri_Commissioni_Sismiche',
        // set to use core fonts only
        'mode' => Pdf::MODE_CORE, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_BROWSER, 
        // your html content input
        'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        // any css to be embedded if required
        'cssInline' => '.kv-heading-1{font-size:18px}', 
         // set mPDF properties on the fly
        'options' => ['title' => 'Prospetto Riepilogo Contributi Pratiche Sismiche'],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>['Comune di Campoli del Monte Taburno - Ufficio Tecnico'], 
            'SetFooter'=>['{PAGENO}'],
        ]
    ]);
    
    // return the pdf output as per the destination setting
    return $pdf->render(); 

    }
    
    
    
    
    
    
    
    
    
     /**
     * Visualizza un Prospetto in formato PDF
     * contenente il report delle somme ancora da pagare 
     * alle commissioni sismiche
     * @param integer $id
     * @return mixed
     */
    public function actionRipartocommissioni()
    {
        $pratichedapagare=Sismica::find()
                ->with('richiedente')
                ->where(['>','ImportoContributo',0])
                ->andWhere(['PAGATO_COMMISSIONE' => 0])
                ->all();
        $totale = Sismica::find()
                ->with('richiedente')
                ->where(['>','ImportoContributo',0])
                ->andWhere(['PAGATO_COMMISSIONE' => 0])
                ->sum('ImportoContributo');
        
	                      
     if (!isset($pratichedapagare)) {
         Yii::$app->session->setFlash('error', "Si è verificato un errore! Non sono rieuscito a generare il Prospetto.");
        return $this->redirect(Yii::$app->request->referrer);
     }
     
    $content =  
'
<p><strong>PROSPETTO Riparto Commissione Sismica' .'</strong></p>
<p>Riepilogo Contributo Incassato per Pratiche Sismiche (Non Pagate alla Commissione) :</p>
<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="64" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Protocollo</th>
    <th width="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Data Protocollo</th>
    <th width="110" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Richiedente</th>
    <th width="66" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Descrizione Lavori</th>
    <th width="56" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Num. Autorizzazione</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Data Autorizzazione</th>
    <th width="104" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Importo Pagato</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Data Pagamento</th>
  </tr>';
//    $tipo=['Rata','Unica'];
    $riga='';
    $formatter = \Yii::$app->formatter;

    foreach ($pratichedapagare as $pratica) {
//        $rp='Si';
        $dp='';
        $da='';
//        if ($rata->pagata==0) {$rp='No';}
        if (!is_null($pratica->DataVersamentoContributo)) {$dp=date('d-m-Y',strtotime($pratica->DataVersamentoContributo));}
        if (!is_null($pratica->DataAUTORIZZAZIONE)) {$da=date('d-m-Y',strtotime($pratica->DataAUTORIZZAZIONE));}
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->Protocollo .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. date('d-m-Y',strtotime($pratica->DataProtocollo)) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->richiedente->nomeCompleto .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->DescrizioneLavori .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->NumeroAUTORIZZAZIONE .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $da .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->ImportoContributo . '</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $dp .'</td><tr>';
         $content = $content . $riga; 
     }
  $riga='';
   $riga = $riga . '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center"></td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center"></td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center"></td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center"></td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center"></td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:right">TOTALE</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $totale . '</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center"></td><tr>';
         $content = $content . $riga; 
         
 $content=$content . ' 
     
</table>
<div><p></p></div>
<div><p></p></div>
<p>RIPARTO SOMME :</p>
<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="120" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">DESTINAZIONE</th>
    <th width="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">%</th>
    <th width="110" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Importo</th>
  </tr>';

    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Commissione</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">65%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.65,2) .'</td><tr>';
         $content = $content . $riga; 
    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Responsabile Procedimento</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">20%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.20,2) .'</td><tr>';
         $content = $content . $riga; 

    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Supporto al RUP</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">20%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.05,2) .'</td><tr>';
         $content = $content . $riga; 

    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Spese Ufficio Sismica</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">10%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.10,2) .'</td><tr>';
         $content = $content . $riga; 
         
         
         
         $content=$content . ' 
     
</table>
<div><p></p></div>
<div><p></p></div>
<p>RIPARTO SOMME FRA I COMPONENTI DELLA COMMISSIONE :</p>
<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="120" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Componente</th>
    <th width="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">%</th>
    <th width="110" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Importo</th>
  </tr>';

    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Ing. Gianluigi DallAglio</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">17%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.17,2) .'</td><tr>';
         $content = $content . $riga; 
    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Ing. Antonio Trosino</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">16%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.16,2) .'</td><tr>';
         $content = $content . $riga; 

    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Ing. Giuseppe Rapuano</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">16%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.16,2) .'</td><tr>';
         $content = $content . $riga; 

    $riga='';
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">Ing. Elena Vetrone</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">16%</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $formatter->asDecimal($totale*0.16,2)  .'</td><tr>';
         $content = $content . $riga; 
         
         
         
         $content=$content . ' 
     
</table>
<div><p></p></div>
<div><p></p></div>
<p>Campoli del Monte Taburno, li '. date("d-m-Y") . '</p>
<p>&nbsp;</p>';       
//$this->renderPartial('_reportView');
    
    // setup kartik\mpdf\Pdf component
    $pdf = new Pdf([
        'filename'=>'Prospetto_Oneri_Commissioni_Sismiche',
        // set to use core fonts only
        'mode' => Pdf::MODE_CORE, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_BROWSER, 
        // your html content input
        'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        // any css to be embedded if required
        'cssInline' => '.kv-heading-1{font-size:18px}', 
         // set mPDF properties on the fly
        'options' => ['title' => 'Prospetto Riepilogo Contributi Pratiche Sismiche'],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>['Comune di Campoli del Monte Taburno - Ufficio Tecnico'], 
            'SetFooter'=>['{PAGENO}'],
        ]
    ]);
    
    // return the pdf output as per the destination setting
    return $pdf->render(); 

    }
    
    
    
   /**
     * Genera il documento word Autorizzazione Sismica con i dati all'interno.
     * 
     * Edit a Word 2007 and newer .docx file.
     * Utilizes the zip extension http://php.net/manual/en/book.zip.php
     * to access the document.xml file that holds the markup language for
     * contents and formatting of a Word document.
     *
     * In this example we're replacing some token strings.  Using
     * the Office Open XML standard ( https://en.wikipedia.org/wiki/Office_Open_XML )
     * you can add, modify, or remove content or structure of the document.
     * @param integer $id
     * @return mixed
     */
    public function actionAutorizzazione($id)
    {
       // require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';

        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        $model = Sismica::findOne($id);
        
//        $pareri = \app\models\PareriCommissioni::find()
//                ->innerJoin('commissioni','pareri_commissioni.commissioni_id=commissioni.idcommissioni')
//                ->innerJoin('tipo_commissioni','commissioni.Tipo=tipo_commissioni.idtipo_commissioni')
//                ->innerJoin('sedute_commissioni','sedute_commissioni.idsedute_commissioni=pareri_commissioni.seduta_id')
//                ->where('pareri_commissioni.pratica_id='.$id)
//                ->One();
                
     
        
        //$dompdfPath = $vendorDirPath . '/dompdf/dompdf';
        $dompdfPath = '@vendor/dompdf/dompdf';
        if (file_exists($dompdfPath)) {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, '@vendor/dompdf/dompdf');
        }

        Settings::loadConfig();
        // Set writers
        $writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');
        // Set PDF renderer
        if (null === Settings::getPdfRendererPath()) {
        $writers['PDF'] = null;
        }

        // Turn output escaping on
        Settings::setOutputEscapingEnabled(true);

        // Return to the caller script when runs by CLI
        if (CLI) {
        return;
        }

        // Create the Object.
        //$zip = new ZipArchive();

        // Use same filename for "save" and different filename for "save as".
        //$filename = 'edilizia\modelli\Mod_ED_04_Permesso_Costruire_Standard_Rev01.docx';
        //$outputFilename = 'edilizia\pratiche\P' . $model->id . '\PermessoCostruire.docx';

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_SM_01_Autorizzazione Sismica.docx');

            // Variables on different parts of document
            //$templateProcessor->setValue('weekday', date('l'));            // On section/content
            //$templateProcessor->setValue('time', date('H:i'));             // On footer
//            $templateProcessor->setValue('Denominazione_Ente','COMUNE DI CAMPOLI DEL MONTE TABURNO' ); //realpath(__DIR__)); // On header
//            $templateProcessor->setValue('Indirizzo','Piazza La Marmora' );
//            $templateProcessor->setValue('NumeroCivico','14' );
//            $templateProcessor->setValue('CAP','82030' );
//            $templateProcessor->setValue('Comune','CAMPOLI DEL MONTE TABURNO' );
//            $templateProcessor->setValue('Provincia','BN' );
//            $templateProcessor->setValue('Telefono','0824/873039' );
//            $templateProcessor->setValue('Fax','0824/873079' );
//            $templateProcessor->setValue('Email','info@comune.campolidelmontetaburno.bn.it' );
//            $templateProcessor->setValue('Pec','campolimt@pec.it' );
            
            //$templateProcessor->setValue('NumeroProtocolloTitolo','991' );
            //$templateProcessor->setValue('DataProtocolloTitolo','16/09/2018' );
            
            $templateProcessor->setValue('NumeroProtocolloTitolo',$model->NumeroProtocolloAutorizzazione );
            $templateProcessor->setValue('NumeroAutorizzazione',$model->NumeroAUTORIZZAZIONE);
            $templateProcessor->setValue('DataAutorizzazione',Yii::$app->formatter->asDate($model->DataAUTORIZZAZIONE,'php:d-m-Y'));
            
            // Collaudatore
            $templateProcessor->setValue('TitoloCollaudatore',$model->collaudatore->titolo->abbr_titolo);
            $templateProcessor->setValue('CognomeCollaudatore',$model->collaudatore->COGNOME);
            $templateProcessor->setValue('NomeCollaudatore',$model->collaudatore->NOME);
            $templateProcessor->setValue('IndirizzoCollaudatore',$model->collaudatore->INDIRIZZO);
            $templateProcessor->setValue('ComuneCollaudatore',$model->collaudatore->COMUNE_RESIDENZA);
            $templateProcessor->setValue('CapCollaudatore',$model->collaudatore->cap_residenza);
            //$templateProcessor->setValue('IndirizzoCollaudatore',$model->collaudatore->INDIRIZZO);
            // Dati istanza
            $templateProcessor->setValue('DataProtocollo',Yii::$app->formatter->asDate($model->DataProtocollo,'php:d-m-Y'));
            $templateProcessor->setValue('Protocollo',$model->Protocollo );
            
            // Richiedente
            $templateProcessor->setValue('Richiedente',$model->richiedente->Cognome . ' ' . $model->richiedente->Nome );
            $templateProcessor->setValue('ComuneNascita',$model->richiedente->ComuneNascita);
            $templateProcessor->setValue('ProvinciaNascita',$model->richiedente->ProvinciaNascita);
            $templateProcessor->setValue('DataNascita',Yii::$app->formatter->asDate($model->richiedente->DataNascita,'php:d-m-Y'));
            $templateProcessor->setValue('ResidenzaComune',$model->richiedente->ComuneResidenza);
            $templateProcessor->setValue('ResidenzaIndirizzo',$model->richiedente->IndirizzoResidenza);
            $templateProcessor->setValue('ResidenzaProvincia',$model->richiedente->ProvinciaResidenza);
            $templateProcessor->setValue('ResidenzaCivico',$model->richiedente->NumeroCivicoResidenza);
            $templateProcessor->setValue('CapRichiedente',$model->richiedente->CapResidenza);
            //$templateProcessor->setValue('DescrizioneIntervento',$model->DescrizioneIntervento);
            $templateProcessor->setValue('Ubicazione',$model->Ubicazione);
            $templateProcessor->setValue('Foglio',$model->CatastoFoglio);
            $templateProcessor->setValue('Particella',$model->CatastoParticelle);
            $templateProcessor->setValue('Sub',$model->CatastoSub);
            $templateProcessor->setValue('CodiceFiscale',$model->richiedente->CodiceFiscale);
            
            $templateProcessor->setValue('DescrizioneIntervento',$model->DescrizioneLavori);
            
            // Materiale
            if ($model->cemento_armato==1) {
            $templateProcessor->setValue('ca','X');
            } else {
            $templateProcessor->setValue('ca','');
            }
            if ($model->muratura_ordinaria==1) {
            $templateProcessor->setValue('mur','X');
            } else {
            $templateProcessor->setValue('mur','');
            }
            if ($model->muratura_armata==1) {
            $templateProcessor->setValue('mra','X');
            } else {
            $templateProcessor->setValue('mra','');
            }
            if ($model->cemento_precompresso==1) {
            $templateProcessor->setValue('cap','X');
            } else {
            $templateProcessor->setValue('cap','');
            }
            if ($model->StrutturaAltro==1) {
            $templateProcessor->setValue('alt','X');
            } else {
            $templateProcessor->setValue('alt','');
            }
            
            if ($model->struttura_metallica==1) {
            $templateProcessor->setValue('acc','X');
            } else {
            $templateProcessor->setValue('acc','');
            }
            
            if ($model->struttura_legno==1) {
            $templateProcessor->setValue('legno','X');
            } else {
            $templateProcessor->setValue('legno','');
            }
            
            
            // date termini inizio lavori e fine lavori
//            $date = date('d-m-Y');
//            $inizio = strtotime ('+1 year',strtotime($date)) ; // facciamo l'operazione
//            $fine = strtotime ('+4 year',strtotime($date)) ; // facciamo l'operazione
//            $inizio = date ('d-m-Y' ,$inizio); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
//            $fine = date ('d-m-Y' ,$fine); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
//            $templateProcessor->setValue('DataInizioPermesso',$inizio);
//            $templateProcessor->setValue('DataFinePermesso',$fine);
            
            
            // COMMISSIONI
            //{DescrizioneCommissioni}
//            if (isset($pareri)) {
//                $commmsg = 'La '. $pareri->commissione->tipologia->descrizione . ' nella seduta del ';
//                if ($pareri->tipoparere_id==2) {
//                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso parere favorevole.';    
//                $templateProcessor->setValue('DescrizionePareri','');
//                } else if ($pareri->tipoparere_id==3){
//                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso il seguente parere favorevole con prescrizioni:';
//                $templateProcessor->setValue('DescrizionePareri',$pareri->testoparere);
//                } else {
//                    $commmsg='';
//                }
//                
//                $templateProcessor->setValue('DescrizioneCommissioni',$commmsg);
//                
//                
//            }
            //$templateProcessor->setValue('DataProposta',Yii::$app->formatter->asDate(date('Y-m-d'),'php:d-m-Y'));
            // Simple table
//            $templateProcessor->cloneRow('rowValue', 10);
//
//            $templateProcessor->setValue('rowValue#1', 'Sun');
//            $templateProcessor->setValue('rowValue#2', 'Mercury');
//            $templateProcessor->setValue('rowValue#3', 'Venus');
//            $templateProcessor->setValue('rowValue#4', 'Earth');
//            $templateProcessor->setValue('rowValue#5', 'Mars');
//            $templateProcessor->setValue('rowValue#6', 'Jupiter');
//            $templateProcessor->setValue('rowValue#7', 'Saturn');
//            $templateProcessor->setValue('rowValue#8', 'Uranus');
//            $templateProcessor->setValue('rowValue#9', 'Neptun');
//            $templateProcessor->setValue('rowValue#10', 'Pluto');
//
//            $templateProcessor->setValue('rowNumber#1', '1');
//            $templateProcessor->setValue('rowNumber#2', '2');
//            $templateProcessor->setValue('rowNumber#3', '3');
//            $templateProcessor->setValue('rowNumber#4', '4');
//            $templateProcessor->setValue('rowNumber#5', '5');
//            $templateProcessor->setValue('rowNumber#6', '6');
//            $templateProcessor->setValue('rowNumber#7', '7');
//            $templateProcessor->setValue('rowNumber#8', '8');
//            $templateProcessor->setValue('rowNumber#9', '9');
//            $templateProcessor->setValue('rowNumber#10', '10');
//
//            // Table with a spanned cell
//            $templateProcessor->cloneRow('userId', 3);
//
//            $templateProcessor->setValue('userId#1', '1');
//            $templateProcessor->setValue('userFirstName#1', 'James');
//            $templateProcessor->setValue('userName#1', 'Taylor');
//            $templateProcessor->setValue('userPhone#1', '+1 428 889 773');
//
//            $templateProcessor->setValue('userId#2', '2');
//            $templateProcessor->setValue('userFirstName#2', 'Robert');
//            $templateProcessor->setValue('userName#2', 'Bell');
//            $templateProcessor->setValue('userPhone#2', '+1 428 889 774');
//
//            $templateProcessor->setValue('userId#3', '3');
//            $templateProcessor->setValue('userFirstName#3', 'Michael');
//            $templateProcessor->setValue('userName#3', 'Ray');
//            $templateProcessor->setValue('userPhone#3', '+1 428 889 775');

            //echo date('H:i:s'), ' Saving the result document...', EOL;
            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@sismica') . '/' . trim(sprintf('%09u', $id)) . '/'; 
             //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
            if (!file_exists($path)) {
                FileHelper::createDirectory($path, $mode = 0777, $recursive = true); 
            }
            //$path = Yii::getAlias('@web') . '/pratiche/P' . trim(sprintf('%09u', $id));
//            $realpath = realpath($path);
//            // If it exist, check if it's a directory
//            if (!($realpath !== false AND is_dir($realpath))) 
//            {
//            FileHelper::createDirectory($path);
//            }
            $file=$path . '/AutorizzazioneSismica' . '_' . uniqid('') . '.docx';
            $templateProcessor->saveAs($file);

            //echo getEndingNotes(array('Word2007' => 'docx'), 'Sample_07_TemplateCloneRow.docx');
//            if (!CLI) {
//                include_once 'Sample_Footer.php';
//            }
            // scarica il file generato
            if (file_exists($file)) {
                Yii::$app->response->sendFile($file);
            } 
        
        
        
        
        //return $this->redirect(['index']);
    }
 
    
     public function actionDownload($filename) 
    { 
       
        if (file_exists($filename)) {
            Yii::$app->response->sendFile($filename);
        } else {
            throw new NotFoundHttpException('file ' . $filename .' non trovato');
            Yii::$app->session->setFlash('error', 'Non sono riuscito a trovare il file: ' . $filename);
            
        }
    }
    
      /**
     * Signup new user
     * @return string
     */
    public function actionCancellafile($id,$idpratica)
    {
        $model = \app\models\AllegatiSismica::findOne($id); 
        $model->delete();
        return $this->redirect(['allegati','idpratica'=>$idpratica]);
    }
    
    
    
       
  public function actionAllegati($idpratica) 
    {
        $model = new \app\models\AllegatiSismica();
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $allegati=\app\models\AllegatiSismica::find()
                        ->where(['id_pratica' => $idpratica]);
        $modelp = Sismica::findOne($idpratica);
//        $atecnici=AllegatiFile::find()
//                        ->where(['id_pratica' => $idpratica,'tipologia'=>0,'tipopratica'=>1]);
//	$alegal=AllegatiFile::find()
//                        ->where(['id_pratica' => $idpratica,'tipologia'=>1,'tipopratica'=>1]);
        $dp=new ActiveDataProvider(['query' => $allegati]);
//        $dp2=new ActiveDataProvider(['query' => $alegal]);
        if ($model->load(Yii::$app->request->post())) {
            // get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            //$fileobj = UploadedFile::getInstance($model, 'nome');
            $file = UploadedFile::getInstance($model, 'nome');
            $model->nome=$file;
            if (!$model->nome) {
                Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Si è verificato un errore:' ,
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);
                //\Yii::$app->getSession()->setFlash(Alert::TYPE_ERROR, 'Congratulations!');
                return $this->redirect(['sismica/allegati', 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
            }
            
            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@pathallegati') . 'sismica/' . trim(sprintf('%09u', $idpratica)) . '/'; 
            //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
            if (!file_exists($path)) {
                FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
                }
            $filename = preg_replace('/[-\s]+/', '-',$model->nome->baseName) . '_' . uniqid('') . '.' . $model->nome->extension;
            $filePath = $path . $filename;
            $model->nome->name=$filename;
            //$filePath = $path . preg_replace('/[-\s]+/', '-', $fileobj->name) . '_' . uniqid('');
            if (!$model->nome->saveAs($filePath,false)) {
            // $this->nome->saveAs('allegatitecnici/' . $this->nome->baseName . '.' . $this->nome->extension);
                Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Si è verificato un errore nel caricare il file!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);    
                return $this->redirect(['sismica/allegati', 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
            }
                //\Yii::$app->getSession()->setFlash(Alert::TYPE_ERROR, 'Congratulations!');
                
            if ($model->validate()) { 
                 
//                     // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
                     //$path=Yii::getAlias('@allegati') . '/' . trim(sprintf('%09u', $idpratica)) . '/'; 
//                    //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
//                     if (!file_exists($path)) {
//                     FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
//                     }
//                    
//                    $filename = preg_replace('/[-\s]+/', '-',$fileobj->baseName) . '_' . uniqid('') . '.' . $fileobj->extension;
//                    $filePath = $path . $filename;
//                    //$filePath = $path . preg_replace('/[-\s]+/', '-', $fileobj->name) . '_' . uniqid('');
//                    if ($fileobj->saveAs($filePath)) {
                        // rimuove caratteri speciali dal nome del file e aggiunge un identificatore unico alla fine del nome
                        $model->nomefile =  $filename; 
                        $model->id_pratica=$idpratica;
                        $model->byte= $model->nome->size; //$fileobj->size;
                        $model->tipo= $model->nome->type; //$fileobj->type;
                        $model->path=$path;
                        $model->data_update=date('Y-m-d');
                        $model->tipopratica=1;
                        //$model->tipologia=0
//                    } else {
//                        \Yii::$app->getSession()->setFlash('error', 'Non sono riuscito a salvare il file sul server.');
//                        return $this->redirect(['allegati', 'idpratica'=>$idpratica,'tipopratica'=>1]);
//                    }
                
            
                if($model->save(false)){
                    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Allegato aggiunto con successo!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);
                    return $this->redirect(['allegati', 'idpratica'=>$idpratica,'tipopratica'=>1]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito a salvare nel database i dati! ' . date('Y-m-d'));
                //Yii::$app->session->setFlash('success', 'bla bla 2');
                //Yii::$app->session->setFlash('error', 'bla bla 3');
                }
            } else {
            $errores = $model->getErrors();
            $this->render('allegati', ['model' => $errores,'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
             
            }
        }
        return $this->render('allegati', [
            'model'=>$model, 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
    }
    
    
    
    public function actionSoggetti($idpratica) 
    {
        $model = new \app\models\SoggettiSismica();
        $model->percentuale=100;
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $soggetti=\app\models\SoggettiSismica::find()->where(['sismica_id' => $idpratica]);
        $modelp = Sismica::findOne($idpratica);
        $dp=new ActiveDataProvider(['query' => $soggetti]);
        if ($model->load(Yii::$app->request->post())) {
            
            
                $model->sismica_id=$idpratica;
            if ($model->validate()) { 
                if($model->save()){
                    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Soggetto aggiunto con successo!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);
                    return $this->redirect(['soggetti', 'idpratica'=>$idpratica]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito ad aggiungere il soggetto! ');
                }
            } else {
                $errors = $model->errors;
                //$errores = $model->getErrors();
                //$this->render('soggetti', ['model' => $model,'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
             
            }
        }
        return $this->render('soggetti', [
            'model'=>$model, 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
    }

    
    
    public function actionTecnici($idpratica) 
    {
        $model = new \app\models\TecniciSismica();
        //$model->percentuale=100;
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $tecnici=\app\models\TecniciSismica::find()->where(['sismica_id' => $idpratica]);
        $modelp = Sismica::findOne($idpratica);
        $dp=new ActiveDataProvider(['query' => $tecnici]);
        if ($model->load(Yii::$app->request->post())) {
            
            
                $model->sismica_id=$idpratica;
            if ($model->validate()) { 
                if($model->save()){
                    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Tecnico aggiunto con successo!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);
                    return $this->redirect(['tecnici', 'idpratica'=>$idpratica]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito ad aggiungere il tecnico! ');
                }
            } else {
                $errors = $model->errors;
                //$errores = $model->getErrors();
                //$this->render('soggetti', ['model' => $model,'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
             
            }
        }
        return $this->render('tecnici', [
            'model'=>$model, 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
    }

    
    
    
          /**
     * Signup new user
     * @return string
     */
    public function actionCancellatecnico($id,$idpratica)
    {
        $model = \app\models\TecniciSismica::findOne($id); 
        $model->delete();
        return $this->redirect(['tecnici','idpratica'=>$idpratica]);
    }
    
    
   public function actionAtti($idpratica) 
    {
        //$model = new AllegatiFile();
       $model = new \yii\base\DynamicModel(['modello_id']);
       $model->addRule(['modello_id'], 'safe');
//        $elenco=\app\models\Modulistica::find()
//                        ->where(['categoria'=>3])
//                        ->andWhere(['report'=>0])
//                        ->all();
        $modelp = Sismica::findOne($idpratica);
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['sismica/documento', 'modello_id'=>$model->modello_id,'idpratica'=>$idpratica]);
        }
        
        
        
        return $this->render('atti', [
            'model'=>$model,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
    } 
    
   
  /**
     * Genera il documento word con i dati all'interno:
     * @modello_id è id del modello da utilizzare
     * @idpratica è id della pratica da cui prelevare i dati 
     * 
     * Edit a Word 2007 and newer .docx file.
     * Utilizes the zip extension http://php.net/manual/en/book.zip.php
     * to access the document.xml file that holds the markup language for
     * contents and formatting of a Word document.
     *
     * In this example we're replacing some token strings.  Using
     * the Office Open XML standard ( https://en.wikipedia.org/wiki/Office_Open_XML )
     * you can add, modify, or remove content or structure of the document.
     * @param integer $id
     * @return mixed
     */
    public function actionDocumento($modello_id, $idpratica)
    {
        
//        $modelid = new \yii\base\DynamicModel(['modello_id']);
//         if ($modelid->load(Yii::$app->request->post())) {
//             $idmodello=$modelid->modello_id;
//         }
        $modello=\app\models\Modulistica::findOne($modello_id);
        $filemodello=$modello->path . $modello->nomefile;                
        $model = Sismica::findOne($idpratica);
        
        
       // require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';

        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        //$model = Edilizia::findOne($id);
        
        $pareri = \app\models\PareriCommissioni::find()
                ->innerJoin('commissioni','pareri_commissioni.commissioni_id=commissioni.idcommissioni')
                ->innerJoin('tipo_commissioni','commissioni.Tipo=tipo_commissioni.idtipo_commissioni')
                ->innerJoin('sedute_commissioni','sedute_commissioni.idsedute_commissioni=pareri_commissioni.seduta_id')
                ->where('pareri_commissioni.pratica_id='.$idpratica)
                ->One();
                
                //SELECT * FROM utc.pareri_commissioni p 
                //inner join commissioni 
                //on (p.commissioni_id=commissioni.idcommissioni) 
                //inner join tipo_commissioni on (commissioni.Tipo=tipo_commissioni.idtipo_commissioni) 
                //where (p.pratica_id=78); 
        
        //$dompdfPath = $vendorDirPath . '/dompdf/dompdf';
        $dompdfPath = '@vendor/dompdf/dompdf';
        if (file_exists($dompdfPath)) {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, '@vendor/dompdf/dompdf');
        }

        Settings::loadConfig();
        // Set writers
        $writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');
        // Set PDF renderer
        if (null === Settings::getPdfRendererPath()) {
        $writers['PDF'] = null;
        }

        // Turn output escaping on
        Settings::setOutputEscapingEnabled(true);

        // Return to the caller script when runs by CLI
        if (CLI) {
        return;
        }

        // Create the Object.
        //$zip = new ZipArchive();

        // Use same filename for "save" and different filename for "save as".
        //$filename = 'edilizia\modelli\Mod_ED_04_Permesso_Costruire_Standard_Rev01.docx';
        //$outputFilename = 'edilizia\pratiche\P' . $model->id . '\PermessoCostruire.docx';

            //$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_ED_04_Permesso_Costruire_Standard_Rev02.docx');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filemodello);
        

            // Variables on different parts of document
            //$templateProcessor->setValue('weekday', date('l'));            // On section/content
            //$templateProcessor->setValue('time', date('H:i'));             // On footer
//            $templateProcessor->setValue('Denominazione_Ente','COMUNE DI CAMPOLI DEL MONTE TABURNO' ); //realpath(__DIR__)); // On header
//            $templateProcessor->setValue('Indirizzo','Piazza La Marmora' );
//            $templateProcessor->setValue('NumeroCivico','14' );
//            $templateProcessor->setValue('CAP','82030' );
//            $templateProcessor->setValue('Comune','CAMPOLI DEL MONTE TABURNO' );
//            $templateProcessor->setValue('Provincia','BN' );
//            $templateProcessor->setValue('Telefono','0824/873039' );
//            $templateProcessor->setValue('Fax','0824/873079' );
//            $templateProcessor->setValue('Email','info@comune.campolidelmontetaburno.bn.it' );
//            $templateProcessor->setValue('Pec','campolimt@pec.it' );
            
            //$templateProcessor->setValue('NumeroProtocolloTitolo','991' );
            //$templateProcessor->setValue('DataProtocolloTitolo','16/09/2018' );
            
            $templateProcessor->setValue('NumeroAUTORIZZAZIONE',$model->NumeroAUTORIZZAZIONE );
            $templateProcessor->setValue('DataAUTORIZZAZIONE',Yii::$app->formatter->asDate($model->DataAUTORIZZAZIONE,'php:d-m-Y'));
            
            // Dati istanza
            $templateProcessor->setValue('DataProtocollo',Yii::$app->formatter->asDate($model->DataProtocollo,'php:d-m-Y'));
            $templateProcessor->setValue('NumeroProtocollo',$model->Protocollo );
            
            // Richiedente
            // se trattasi di un soggetto persona fisica 
            if ($model->richiedente->RegimeGiuridico_id==1) {
                $templateProcessor->setValue('Richiedente',$model->richiedente->Cognome . ' ' . $model->richiedente->Nome );    
                $templateProcessor->setValue('Cognome',$model->richiedente->Cognome);    
                $templateProcessor->setValue('Cognome',$model->richiedente->Nome);    
                $templateProcessor->setValue('ComuneNascita',$model->richiedente->ComuneNascita);
                $templateProcessor->setValue('ProvinciaNascita',$model->richiedente->ProvinciaNascita);
                $templateProcessor->setValue('DataNascita',Yii::$app->formatter->asDate($model->richiedente->DataNascita,'d-m-Y'));
            } else {
                $templateProcessor->setValue('Richiedente',$model->richiedente->Denominazione );    
            }
            $templateProcessor->setValue('ResidenzaComune',$model->richiedente->ComuneResidenza);
            $templateProcessor->setValue('ResidenzaIndirizzo',$model->richiedente->IndirizzoResidenza);
            $templateProcessor->setValue('ResidenzaCap',$model->richiedente->CapResidenza);
            $templateProcessor->setValue('ResidenzaProvincia',$model->richiedente->ProvinciaResidenza);
            $templateProcessor->setValue('ResidenzaCivico',$model->richiedente->NumeroCivicoResidenza);
            $templateProcessor->setValue('DescrizioneIntervento',$model->DescrizioneLavori);
            //$templateProcessor->setValue('IndirizzoImmobile',$model->IndirizzoImmobile);
            $templateProcessor->setValue('CatastaleFoglio',$model->CatastoFoglio);
            $templateProcessor->setValue('CatastaleParticelle',$model->CatastoParticelle);
            $templateProcessor->setValue('CodiceFiscale',$model->richiedente->CodiceFiscale);
            $templateProcessor->setValue('ImportoContributo',number_format (floatval($model->ImportoContributo),2,',','.'));
            
            
            
            if (isset($model->CatastoSub)) {
                $templateProcessor->setValue('CatastoSub',' Sub.' . $model->CatastoSub);
            } else {
                $templateProcessor->setValue('CatastoSub',' ');
            }
            $intestato = $model->richiedente->Cognome . ' ' . $model->richiedente->Nome . ' nato a ' . $model->richiedente->ComuneNascita . ' (' . $model->richiedente->ProvinciaNascita . ')';
            $templateProcessor->setValue('DescrizioneIntestatario',$intestato);
            
            // date termini inizio lavori e fine lavori
            $date = date('d-m-Y');
            $inizio = strtotime ('+1 year',strtotime($date)) ; // facciamo l'operazione
            //$fine = strtotime ('+4 year',strtotime($date)) ; // facciamo l'operazione
            $inizio = date ('d-m-Y' ,$inizio); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
            //$fine = date ('d-m-Y' ,$fine); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
            $templateProcessor->setValue('DataInizioLavori',$inizio);
            //$templateProcessor->setValue('DataFinePermesso',$fine);
            
            
            // COMMISSIONI
            //{DescrizioneCommissioni}
            if (isset($pareri)) {
                $commmsg = 'La '. $pareri->commissione->tipologia->descrizione . ' nella seduta del ';
                if ($pareri->tipoparere_id==2) {
                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso parere favorevole.';    
                $templateProcessor->setValue('DescrizionePareri','');
                } else if ($pareri->tipoparere_id==3){
                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso il seguente parere favorevole con prescrizioni:';
                $templateProcessor->setValue('DescrizionePareri',$pareri->testoparere);
                } else {
                    $commmsg='';
                }
                
                $templateProcessor->setValue('DescrizioneCommissioni',$commmsg);
                
                
            }
            $templateProcessor->setValue('DataProposta',Yii::$app->formatter->asDate(date('Y-m-d'),'php:d-m-Y'));
            // Simple table
//            $templateProcessor->cloneRow('rowValue', 10);
//
//            $templateProcessor->setValue('rowValue#1', 'Sun');
//            $templateProcessor->setValue('rowValue#2', 'Mercury');
//            $templateProcessor->setValue('rowValue#3', 'Venus');
//            $templateProcessor->setValue('rowValue#4', 'Earth');
//            $templateProcessor->setValue('rowValue#5', 'Mars');
//            $templateProcessor->setValue('rowValue#6', 'Jupiter');
//            $templateProcessor->setValue('rowValue#7', 'Saturn');
//            $templateProcessor->setValue('rowValue#8', 'Uranus');
//            $templateProcessor->setValue('rowValue#9', 'Neptun');
//            $templateProcessor->setValue('rowValue#10', 'Pluto');
//
//            $templateProcessor->setValue('rowNumber#1', '1');
//            $templateProcessor->setValue('rowNumber#2', '2');
//            $templateProcessor->setValue('rowNumber#3', '3');
//            $templateProcessor->setValue('rowNumber#4', '4');
//            $templateProcessor->setValue('rowNumber#5', '5');
//            $templateProcessor->setValue('rowNumber#6', '6');
//            $templateProcessor->setValue('rowNumber#7', '7');
//            $templateProcessor->setValue('rowNumber#8', '8');
//            $templateProcessor->setValue('rowNumber#9', '9');
//            $templateProcessor->setValue('rowNumber#10', '10');
//
//            // Table with a spanned cell
//            $templateProcessor->cloneRow('userId', 3);
//
//            $templateProcessor->setValue('userId#1', '1');
//            $templateProcessor->setValue('userFirstName#1', 'James');
//            $templateProcessor->setValue('userName#1', 'Taylor');
//            $templateProcessor->setValue('userPhone#1', '+1 428 889 773');
//
//            $templateProcessor->setValue('userId#2', '2');
//            $templateProcessor->setValue('userFirstName#2', 'Robert');
//            $templateProcessor->setValue('userName#2', 'Bell');
//            $templateProcessor->setValue('userPhone#2', '+1 428 889 774');
//
//            $templateProcessor->setValue('userId#3', '3');
//            $templateProcessor->setValue('userFirstName#3', 'Michael');
//            $templateProcessor->setValue('userName#3', 'Ray');
//            $templateProcessor->setValue('userPhone#3', '+1 428 889 775');

            //echo date('H:i:s'), ' Saving the result document...', EOL;
            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@pathsismica') . '/' . trim(sprintf('%09u', $idpratica)) . '/'; 
             //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
            if (!file_exists($path)) {
                FileHelper::createDirectory($path, $mode = 0777, $recursive = true); 
            }
            //$path = Yii::getAlias('@web') . '/pratiche/P' . trim(sprintf('%09u', $id));
//            $realpath = realpath($path);
//            // If it exist, check if it's a directory
//            if (!($realpath !== false AND is_dir($realpath))) 
//            {
//            FileHelper::createDirectory($path);
//            }
            $file=$path . '/documento' . '_' . uniqid('') . '.docx';
            $templateProcessor->saveAs($file);

            //echo getEndingNotes(array('Word2007' => 'docx'), 'Sample_07_TemplateCloneRow.docx');
//            if (!CLI) {
//                include_once 'Sample_Footer.php';
//            }
            // scarica il file generato
            if (file_exists($file)) {
                Yii::$app->response->sendFile($file);
            } 
        
        
        
        
        //return $this->redirect(['index']);
    }
 
    
}
