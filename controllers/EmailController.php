<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use kartik\dynagrid\models\DynaGridConfig;
use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Shared;
use PhpOffice\PhpWord\Settings;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use kekaadrenalin\imap;
use kekaadrenalin\imap\Mailbox;
//use kekaadrenalin\yii2imap\Imap;

class EmailController extends \yii\web\Controller
{

    
//     public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                //'only' => ['logout'],
//                'rules' => [
//                    [
//                        'allow'=>true,
//                        'actions' => ['index'],
//                        'roles' => ['@'],
//                    ],
//                ],
//                 'denyCallback' => function ($rule, $action){
//                // everything else is denied
//                    if(Yii::$app->request->referrer){
//                        return $this->redirect(Yii::$app->request->referrer);
//                    } else {
//                        return $this->goHome();
//                    }
//                
//                },
//           ],  
//        ];
//    }

    
/**
     * Visualizza la Homepage della email
     *
     * @return string
     */
    public function actionIndex()
    {
        //  $this->layout = 'yourNewLayout';
                 //$query = Lavori::find();
                 //$item=array();
        //        $this->layout = 'main';
        //	 $searchModel = new CduSearch;
        //	 $model = new DynaGridConfig();
        //	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //         $dataProvider->sort->defaultOrder = ['DataProtocollo' => SORT_DESC];
                //$dataProvider->pagination = ['pageSize' => 15];
        $mailbox = new \kekaadrenalin\imap\Mailbox(Yii::$app->imap->connection);
      $MailboxInfo = $mailbox->getMailboxInfo();   
      //Size
      $mail1 = $mailbox->searchMailBox($criteria = 'ALL');
      $mailIds = $mailbox->sortMails($criteria = SORTDATE);
      
        //        foreach($mailIds as $mailId)
        //            {
        //                // Returns Mail contents
        //                $mail = $mailbox->getMail($mailId); 
        //
        //                // Read mail parts (plain body, html body and attachments
        //                $mailObject = $mailbox->getMailParts($mail);
        //
        //                // Array with IncomingMail objects
        //                //print_r($mailObject);
        //
        //                // Returns mail attachements if any or else empty array
        //                $attachments = $mailObject->getAttachments(); 
        //                foreach($attachments as $attachment){
        //                   // echo ' Attachment:' . $attachment->name . PHP_EOL;
        //
        //                    // Delete attachment file
        //                    unlink($attachment->filePath);
        //                }
        //            } 
         
         
         
         

        return $this->render('inbox',['mailbox' => $mailbox,'email' => $mailIds,'MailboxInfo'=>$MailboxInfo]);
    }
    
    
    
   /**
     * Visualizza la Homepage della email
     *
     * @return string
     */
    public function actionReademail($mailid)
    {
     $mailbox = new \kekaadrenalin\imap\Mailbox(Yii::$app->imap->connection);
      // $MailboxInfo = $mailbox->getMailboxInfo();   
      // Size
     
      $mail = $mailbox->getMail($mailid);
      
        
        return $this->render('read',['mail' => $mail, 'mailbox'=>$mailbox]);
    
        
    } 
    
    
    
    
    
    
    
    /**
     * Signup new user
     * @return string
     */
    public function actionDelete($id)
    {
        $model = Cdu::findOne($id); 
        $model->delete();
        return $this->redirect(['index']);
    }
    
    
    
     /**
     * Signup new user
     * @return string
     */
    public function actionUpdate($id)
    {
    
	$model = Cdu::findOne($id);
            

     if ($model->load(Yii::$app->request->post())) {
         if ($model->validate()) {
            if ($model->save()) {
             echo Yii::$app->session->setFlash('success', "Richiesta Aggiornata!");
             return $this->redirect(['index']);
           } else {
               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. La Pratica non è stata aggiornata!");
           }
         }
    } 
    return $this->render('modifica',['model' => $model]);

  }
    
    
    
    
    
    
    
     public function actionNuova()
    {
/**
     * Crea una nuova email.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
        $model = new Cdu();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
//                $model->Stato_Pratica_id=1;
//                $model->TitoloOneroso=0;
                $model->DataProtocollo=date('Y-m-d',strtotime($model->DataProtocollo));
                $model->save();
                //return $this->redirect(['view', 'id' => $model->edilizia_id]);
                return $this->redirect(['index']);
            // all inputs are valid
            } else {
            // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }
        //$model->id_titolo=4;
        return $this->render('nuovo', [
            'model' => $model,
        ]);
     
    }
    
   
 
}