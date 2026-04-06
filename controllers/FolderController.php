<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\filters\HttpCache;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use app\models\Folder;
use app\models\Project;
//use app\models\User;
use mdm\admin\models\User;
use app\models\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use yii\data\ActiveDataProvider;
use yii\db\Query;


class FolderController extends Controller
{
    public $enableCsrfValidation = true;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'edit', 'delete', 'upload', 'download'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

public function actionRedirect($projectId)
    {
        $userId = Yii::$app->user->id;
        $user = User::findOne($userId);

        Yii::$app->session->set('userName', $user->Name);
        Yii::$app->session->set('projid', $projectId);

        return $this->redirect(['index', 'projectId' => $projectId]);
    }


    // public function actionIndex($projectId)
    // {
    //     $project = Project::find()->with(['folders.creatorUser', 'files.creatorUser', 'users'])->where(['id' => $projectId])->one();
    //     $user = User::findOne(Yii::$app->user->id);

    //     return $this->render('index', [
    //         'project' => $project,
    //         'user' => $user,
    //     ]);
    // }

    public function actionIndexold($projectId,$id=null)
        {
            // Passiamo ProjectId alla view
            $projId = (int)$projectId;
            $this->layout = 'mainbim';
            Yii::$app->view->params['projectId'] = $projectId;
            // Carichiamo il progetto con le relazioni
            $project = Project::find()
                ->where(['id' => $projId])
                ->with(['folders.creatorUser', 'files.creatorUser'])
                ->one();
            $currentFolder = $id ? Folder::findOne($id) : Folder::find()->where(['ParentId' => null])->one();

            if ($project === null) {
                throw new \yii\web\NotFoundHttpException('Project not found.');
            }
            Yii::$app->view->params['Name'] = $project->Name;

            // Otteniamo l'utente corrente
            $userId = Yii::$app->user->id;
            $user = User::find()
                ->where(['id' => $userId])
                ->with(['sharedIssues'])
                ->one();

            // File del progetto senza cartella (FolderId = NULL)
            $projectFiles = File::find()
                ->where(['ProjectId' => $projId, 'FolderId' => null])
                ->all();

            // Tutte le cartelle con il creatore
            $folders = Folder::find()
                ->with(['creatorUser'])
                ->all();

            // Render della vista con variabili
            return $this->render('index2', [
                'projId' => $projId,
                'projectName' => $project->Name,
                'user' => $user,
                'projectFiles' => $projectFiles,
                'folders' => $folders,
                'currentFolder' => $currentFolder,
            ]);
        }


         public function actionIndex($projectId,$id=null)
        {
            // Passiamo ProjectId alla view
            $projId = (int)$projectId;
            $this->layout = 'mainbim';
            Yii::$app->view->params['projectId'] = $projectId;
            // Carichiamo il progetto con le relazioni
            $project = Project::find()
                ->where(['id' => $projId])
                ->with(['folders.creatorUser', 'files.creatorUser'])
                ->one();
            $currentFolder = $id ? Folder::findOne($id) : Folder::find()->where(['ParentId' => null])->one();

            if ($project === null) {
                throw new \yii\web\NotFoundHttpException('Progetto non trovato.');
            }
            Yii::$app->view->params['Name'] = $project->Name;

            // Otteniamo l'utente corrente
            $userId = Yii::$app->user->id;
            $user = User::find()
                ->where(['id' => $userId])
                ->with(['sharedIssues'])
                ->one();

            // File del progetto senza cartella (FolderId = NULL)
            $projectFiles = File::find()
                ->where(['ProjectId' => $projId, 'FolderId' => null])
                ->all();

            $fileProvider = new ActiveDataProvider([
            'query' => File::find()->where(['FolderId' => $currentFolder->id ?? null]),
            ]);

            // // tutte le cartelle (per il tree)
            // $folders = Folder::find()->all();
            // Tutte le cartelle con il creatore
            $folders = Folder::find()
                ->with(['creatorUser'])
                ->all();

            // Render della vista con variabili
            return $this->render('index', [
                'projId' => $projId,
                'projectName' => $project->Name,
                'user' => $user,
                'fileProvider' => $fileProvider,
                'files' => $projectFiles,
                'folders' => $folders,
                'currentFolder' => $currentFolder,
                'selId'=>$id
            ]);
        }





    // Fornisce la struttura ad albero in JSON per jsTree
    public function actionTreeData($projectId,$id = '#')
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    if ($id === '#') {
        // Nodo radice unico
        return [[
            'id'       => 'root',
            'text'     => '',
            'children' => true,
            'icon'     => 'jstree-folder' //'img/root_folder_4.png',
            // 'li_attr' => ['class' => 'jstree-root-node']
        ]];
    }

    if ($id === 'root') {
        // Carichiamo le cartelle effettive senza parent (quelle di primo livello)
        $folders = (new \yii\db\Query())
            ->from('Folders')
            ->orWhere(['ParentId' => 0,'projectId'=>$projectId])
            ->orderBy(['Id' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($folders as $f) {
            $result[] = [
                'id'       => (string)$f['Id'],
                'text'     => $f['Name'] ?? ('Folder ' . $f['Id']),
                'children' => true,
                'icon'     => 'jstree-folder',
                
            ];
        }
        return $result;
    }

    // Caricamento figli “normali”
    $folders = (new \yii\db\Query())
        ->from('Folders')
        ->where(['ParentId' => (int)$id,'projectId'=>$projectId])
        ->orderBy(['Id' => SORT_ASC])
        ->all();

    $result = [];
    foreach ($folders as $f) {
        $result[] = [
            'id'       => (string)$f['Id'],
            'text'     => $f['Name'] ?? ('Folder ' . $f['Id']),
            'children' => true,
            'icon'     => 'jstree-folder',
            
        ];
    }
    return $result;
}


    // Restituisce i contenuti (folders + files) della cartella selezionata
    public function actionFolderContent($projectId,$id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = (string)$id;
        $id = (int)$id;
        if ($ids === 'root') {
            $folders = (new Query())
                ->select(['f.Id', 'f.Name','f.CreationDate','f.CreatorUserId','u.Name AS CreatorName'])
                ->from(['f'=>'Folders'])
                ->leftJoin(['u' => 'user'], 'u.Id = f.UserId')
                ->where(['f.ParentId' => 0])
                ->andWhere(['f.projectId'=>$projectId])
                ->orderBy(['f.Name' => SORT_ASC, 'f.Id' => SORT_ASC])
                ->all();
         }
        else
        {
            $folders = (new Query())
                ->select(['f.Id', 'f.Name','f.CreationDate','f.CreatorUserId','u.Name AS CreatorName'])
                ->from(['f'=>'Folders'])
                ->leftJoin(['u' => 'user'], 'u.id = f.CreatorUserId')
                ->where(['f.ParentId' => $id])
                ->andWhere(['f.projectId'=>$projectId])
                ->orderBy(['f.Name' => SORT_ASC, 'f.Id' => SORT_ASC])
                ->all();
            }
        //$folders = Folder::find()->where(['parentId' => $id])->asArray()->all();
        if ($ids === 'root') {
                $files = (new Query())
                    ->select(['s.Id', 's.Name','s.UploadDate','s.CreatorUserId','s.Type','u.Name AS CreatorName'])
                    ->from(['s'=>'Files'])
                    ->leftJoin(['u' => 'user'], 'u.id = s.CreatorUserId')
                    ->where(['IS','s.FolderId',null])
                    ->andWhere(['s.projectId'=>$projectId])
                    ->orderBy(['s.Name' => SORT_ASC, 's.Id' => SORT_ASC])
                    ->all();
         }
        else
        {
                $files = (new Query())
                    ->select(['s.Id', 's.Name','s.UploadDate','s.CreatorUserId','s.Type','u.Name AS CreatorName'])
                    ->from(['s'=>'Files'])
                    ->leftJoin(['u' => 'user'], 'u.id = s.CreatorUserId')
                    ->where(['s.FolderId' => $id,'s.projectId'=>$projectId])
                    ->orderBy(['Name' => SORT_ASC, 'Id' => SORT_ASC])
                    ->all();
         }
        //$files   = File::find()->where(['folderId' => $id])->asArray()->all();
        $userId = Yii::$app->user->id;
            $user = User::find()
                ->where(['id' => $userId])
                ->with(['sharedIssues'])
                ->one();
        return [
            'folders' => $folders,
            'files'   => $files,
            'prid'    => (int)$projectId,
            'cuid'    => Yii::$app->user->id,
        ];
    }








    public function actionInnerDet($id, $projectId)
        {
            if (!$id) {
                return $this->redirect(['index', 'projectId' => $projectId]);
            }

            $folder = Folder::find()
                ->where(['id' => $id])
                ->with(['innerFolders', 'files.creatorUser', 'project', 'creatorUser'])
                ->one();

            if (!$folder) {
                throw new NotFoundHttpException('Cartella non trovata.');
            }

            return $this->render('inner-det', [
                'folder' => $folder,
                'projectId' => $projectId
            ]);
        }

    public function actionCreate($projectId,$parentId = null)
    {
        $model = new Folder();
        $model->ProjectId = $projectId;
        $model->ParentId = $parentId ?: 0;
        
        if ($parentId>0) { $model->HasParent =true;  }  
        $model->CreatorUserId= Yii::$app->user->id;
        $model->UserId= Yii::$app->user->id; // da verificare a cosa serve questo campo
        $model->CreationDate = date('Y-m-d H:i:s');
        if ($model->load(Yii::$app->request->post()) ) {
            $project = Project::findOne($projectId);
            // $model->HasParent = false;
            // $model->ParentId = 0;
            
            $bDir = Yii::getAlias('@webroot/files/') . 'P'. sprintf("%04d", $projectId);
            
            if ($parentId==='root') {
                 $model->HasParent =false;
                 // siamo nella root directory del progetto
                 // vediamo se è stata già creata la cartella del progetto P%%%N
                 // se non è stata creata la creiamo
                if (!is_dir($bDir)) {
                    // non esiste la cartella principale del progetto e quindi la creo
                    $oldMask=umask(000);
                   if (!mkdir($bDir, 0777,false)) {
                        die('Failed to create directories...');
                        Yii::error('La directory non è stata creata', 'application');
                    };
                    umask($oldMask);

                }    
                $rDir ='files/'.'P'. sprintf("%04d", $projectId);
                 // vediamo se la cartella già esiste sul disco
                 $folderName = $bDir."/". $model->Name;
                 $model->Path = $rDir."/". $model->Name;;
                if (!is_dir($folderName)) {
                    // non esiste la cartella principale del progetto e quindi la creo
                    $oldMask=umask(000);
                   if (!mkdir($folderName, 0777,true)) {
                        die('Creazione fallita...');
                        Yii::error('La directory non è stata creata', 'application');
                    };
                    umask($oldMask);
                }    
            } else {
                $model->HasParent =true;
                // la cartella corrente è una sub-directory
                $parent = Folder::FindOne($parentId);
                $model->Path = $parent->Path . "/".$model->Name;
                $aDir = Yii::getAlias('@webroot/') . $parent->Path . "/".$model->Name;
                // path della cartella superiore + / Nome della cartella
                $oldMask=umask(000);
                if (!mkdir($aDir, 0777,false)) {
                    die('Failed to create directories...');
                };
                umask($oldMask);
            } 
            $model->save();
            $project->link('folders', $model);
            return $this->redirect(['index', 'projectId' => $projectId]);
        }

        $project = Project::findOne($projectId);
        $users = User::find()->all();

        return $this->render('create', [
            'model' => $model,
            'project' => $project,
            'users' => $users,
            'parentId' => $parentId
        ]);
    }

    // // GET: Create Inner Folder
    // public function actionCreateInnerFolder($id = null)
    // {
    //     return $this->render('create-inner-folder', [
    //         'parentId' => $id,
    //         'model' => new Folder(),
    //     ]);
    // }

    // POST: Create Inner Folder
    public function actionCreateInnerFolder($id,$projectId)
    {
        $model = new Folder();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Recupero la cartella padre
            $parent = Folder::findOne($id);
            if (!$parent) {
                throw new NotFoundHttpException('Parent folder not found.');
            }

            // Imposto i dati derivati
            $model->ProjectId = $projectId; //$parent->ProjectId;
            $model->HasParent = true;
            $model->ParentId = $id;
            $model->CreatorUserId = Yii::$app->user->id; 

            if ($model->save(false)) {
                return $this->redirect(['inner-det', 'id' => $id,'projectId']);
            }
        }

        return $this->render('create-inner-folder', [
            'parentId' => $id,
            'projectId'=> $projectId,
            'model' => $model,
        ]);
    }


    public function actionEdit($id)
    {
        $folder = Folder::findOne($id);
        // if (!$model) {
        //     throw new NotFoundHttpException("Folder not found");
        // }

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['index', 'projectId' => $model->ProjectId]);
        // }
        if ($folder->load(Yii::$app->request->post()) && $folder->validate()) {
            if ($folder->save()) {
                if ($folder->parent_id == 0) {
                    return $this->redirect(['index', 'projectId' => $folder->ProjectId]);
                } else {
                    return $this->redirect(['inner-det', 'id' => $folder->ParentId, 'projectId' => $folder->ProjectId]);
                }
            }
        }
        return $this->render('edit', [
            'folder' => $folder,
        ]);
    }

    /**
     * Cancella cartella
     */
    public function actionDelete($id, $projectId)
    {
        $folder = Folder::findOne($id);
        $this->deleteRecursive($folder);

        if ($folder->ParentId == 0) {
            return $this->redirect(['index', 'projectId' => $projectId]);
        } else {
            return $this->redirect(['inner-det', 'id' => $folder->ParentId, 'projectId' => $projectId]);
        }
    }

    /**
     * Cancella cartella e sottocartelle
     */
    private function deleteRecursive($folder)
        {
            foreach ($folder->innerFolders as $inner) {
                $this->deleteRecursive($inner);
            }
            foreach ($folder->files as $file) {
                $file->delete();
            }
            $folder->delete();
        }



public function actionAjaxUpload($id, $projectId = null)
    {
        $folder = Folder::findOne($id);
        if (!$folder) {
            throw new NotFoundHttpException("Folder not found");
        }
        Yii::$app->response->format = Response::FORMAT_JSON;


        $files = UploadedFile::getInstancesByName('files');
        $uploadDir = Yii::getAlias('@webroot/files');
        foreach ($files as $file) {
            $fileName = uniqid() . '_' . $id. '.'. $file->extension;
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

             $newFile = new File([
                // $model = new File(),
                // $model->Name = $file->baseName,
                // $model->Type = $file->type,
                // $model->path = realpath($filePath), //$filePath;
                // $model->ProjectId = $folder->ProjectId,
                // $model->CreatorUserId = Yii::$app->user->id,
                // $model->FolderId = $folder->id,
                'Name' => $fileName,
                'Type' => '.' . $file->extension,
                'Path' => realpath($filePath . '/' . $fileName),
                'ProjectId' =>  $folder->ProjectId,
                'CreatorUserId' => Yii::$app->user->id,
             ]);

            if ($newFile->save()) {
                $folder->link('files', $newFile);
                return ['success' => true, 'id' => $id];
            }
        }
        // $model = new File();
        // $model->uploadFile = UploadedFile::getInstanceByName('file');

        // if ($model->uploadFile && $model->upload()) {
        //     $model->project_id = $projectId;
        //     if ($model->save(false)) {
        //         return ['success' => true, 'id' => $model->id, 'name' => $model->name];
        //     }
        // }

        return ['success' => false, 'error' => 'Upload fallito o validazione non superata'];
    }




    /**
     * Upload multiplo di file in una cartella
     */
    public function actionUploadFiles2($id)
    {
        $folder = Folder::findOne($id);
        if (!$folder) {
            throw new NotFoundHttpException("Folder not found");
        }

        $files = UploadedFile::getInstancesByName('files');
        $uploadDir = Yii::getAlias('@webroot/files');

        foreach ($files as $file) {
            $fileName = uniqid() . '_' . $id. '.'. $file->extension;
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

             $newFile = new File([
                // $model = new File(),
                // $model->Name = $file->baseName,
                // $model->Type = $file->type,
                // $model->path = realpath($filePath), //$filePath;
                // $model->ProjectId = $folder->ProjectId,
                // $model->CreatorUserId = Yii::$app->user->id,
                // $model->FolderId = $folder->id,
                'Name' => $fileName,
                'Type' => '.' . $file->extension,
                'Path' => realpath($filePath . '/' . $fileName),
                'ProjectId' =>  $folder->ProjectId,
                'CreatorUserId' => Yii::$app->user->id,
             ]);

            if ($newFile->save()) {
                $folder->link('files', $newFile);
            }
        }

        return $this->redirect(['inner-det', 'id' => $id]); // view', 'id' => $folder->id]);
    }


    /**
     * Upload multiplo di file direttamente nel progetto
     */
    public function actionUploadFiles($projectId)
    {
        $project = Project::findOne($projectId);
        $uploadedFiles = UploadedFile::getInstancesByName('files');
        //$uploadPath = Yii::getAlias('@webroot') . '/files'. '/P'. sprintf("%04d", $projectId);
        $folderId = Yii::$app->request->post('folderId');
        //var_dump($folderId); die;
        $fapath=Yii::getAlias('@webroot');
        $frpath='files/P'. sprintf("%04d", $projectId);
        if ($folderId ==='root') {
            $fapath = $fapath . '/files/P'. sprintf("%04d", $projectId);
        } else  {   
            // sto caricando file in una sub-directory del progetto
            $folder=Folder::findOne($folderId);
            $fapath=$fapath . '/'.$folder->Path;
            $frpath=$folder->Path;
        }    
        // var_dump($folderId); 
        // var_dump($fapath); 
        // var_dump($frpath); 
        
        
        foreach ($uploadedFiles as $file) {
            $fileName = pathinfo($file->baseName, PATHINFO_FILENAME) . '@' . $projectId . '.' . $file->extension;
            //var_dump($file->error, $file->tempName);
            if ($file->error !== UPLOAD_ERR_OK) {
                throw new \Exception("Errore upload: " . $file->error);
            }

            if (!is_dir($fapath)) {
                throw new \Exception("Directory non trovata: " . $fapath);
            }

            if (!$file->saveAs($fapath . '/' . $fileName)) {
                throw new \Exception("Salvataggio fallito in: " . $fapath . '/' . $fileName);
            }
            //var_dump($fapath, $fileName, $file->saveAs($fapath . '/' . $fileName));
            

            $newFile = new File();
            $newFile->Name = $fileName;
            $newFile->Type = '.' . $file->extension;
            $newFile->Path = $frpath;
            $newFile->FolderId = $folderId;
            $newFile->ProjectId = $projectId;
            $newFile->UserId=Yii::$app->user->id;
            $newFile->CreatorUserId = Yii::$app->user->id;
            $newFile->UploadDate = date('Y-m-d H:i:s');
            

            if (!$newFile->save()) {
                Yii::error($newFile->errors, __METHOD__);
                throw new \Exception("Errore salvataggio File: " . json_encode($newFile->errors));
            } else {    
                $project->link('files', $newFile);
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true, 'folderId' => $folderId];
       // return $this->redirect(['index', 'projectId' => $projectId]);
    }


    /**
     * Upload multiplo di file direttamente nel progetto
     */
    public function actionMultipleFilesInProject($projectId)
    {
        $project = Project::findOne($projectId);
        $uploadedFiles = UploadedFile::getInstancesByName('files');
        $uploadPath = Yii::getAlias('@webroot') . '/files';

        foreach ($uploadedFiles as $file) {
            $fileName = pathinfo($file->baseName, PATHINFO_FILENAME) . '@' . $projectId . '.' . $file->extension;
            $file->saveAs($uploadPath . '/' . $fileName);

            $newFile = new File([
                'Name' => $fileName,
                'Type' => '.' . $file->extension,
                'Path' => realpath($uploadPath . '/' . $fileName),
                'ProjectId' => $projectId,
                'UserId'=>Yii::$app->user->id,
                'CreatorUserId' => Yii::$app->user->id,
            ]);

            if ($newFile->save()) {
                $project->link('files', $newFile);
            }
        }

        return $this->redirect(['index', 'projectId' => $projectId]);
    }

    /**
     * Scarica file
     */

    public function actionDownload($fileName)
    {
        $filePath = Yii::getAlias('@webroot/files/' . $fileName);
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException("File not found");
        }
        if (strpos($fileName, '@') !== false) {
            $name = explode('@', $fileName)[0] . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        } elseif (strpos($fileName, ';') !== false) {
            $name = explode(';', $fileName)[0] . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        } else {
            $name = $fileName;
        }

        return Yii::$app->response->sendFile($name);
    }

    public function actionView($id)
    {
        $model = Folder::find()->with(['files.creatorUser', 'innerFolders'])->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException("Folder not found");
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionViewfile($id)
        {
            $model = File::find()->where(['id' => $id])->one();
            if (!$model) {
                throw new NotFoundHttpException("File not found");
            }
            $htmlOutput=Null;
            //$filePath = $model->Path;//Yii::getAlias('@webroot/files/' . $model->Name);
            $fileExt = strtolower(pathinfo($model->Name, PATHINFO_EXTENSION));
            if ($fileExt === 'xls' || $fileExt === 'xlsx')
               { 
                 $spreadsheet = IOFactory::load($model->Path);
                    // Ottieni il foglio attivo (puoi scegliere anche un foglio specifico)
                    $worksheet = $spreadsheet->getActiveSheet();

                    // Genera HTML
                    $writer = new Html($spreadsheet);
                    ob_start();
                    $writer->save('php://output');
                    $htmlOutput = ob_get_clean();
                }
            if ($fileExt === 'doc' || $fileExt === 'docx')
               { 
                 $doc = \PhpOffice\PhpWord\IOFactory::load($model->Path);

                    // Genera HTML
                    $writer = new \PhpOffice\PhpWord\Writer\HTML($doc);
                    $docname = 'docword111222.html'; //basename($model->Name,$fileExt) . 'html';
                    $htmlfilePath = Yii::getAlias('@webroot/files/' . $docname);
                    //ob_start();
                    $writer->save($htmlfilePath);
                    $htmlOutput = 'files/' . $docname;
                }    
            return $this->render('viewfile', [
                'model' => $model,
                'htmlOutput' => $htmlOutput,
            ]);
        }


    public function actionDeleteFile($id)
    {
        $file = File::findOne($id);
        if (!$file) {
            throw new NotFoundHttpException("File not found");
        }

        $folderId = $file->folder_id;
        $file->delete();

        return $this->redirect(['view', 'id' => $folderId]);
    }

    // public function actionUploadToProject($projectId)
    // {
    //     $project = Project::findOne($projectId);
    //     if (!$project) {
    //         throw new NotFoundHttpException("Project not found");
    //     }

    //     $files = UploadedFile::getInstancesByName('files');
    //     $uploadDir = Yii::getAlias('@webroot/files');

    //     foreach ($files as $file) {
    //         $fileName = uniqid() . '.' . $file->extension;
    //         $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

    //         if ($file->saveAs($filePath)) {
    //             $model = new File();
    //             $model->name = $file->baseName;
    //             $model->type = $file->type;
    //             $model->path = $filePath;
    //             $model->project_id = $project->id;
    //             $model->creator_user_id = Yii::$app->user->id;
    //             $model->save();
    //         }
    //     }

    //     return $this->redirect(['index', 'projectId' => $projectId]);
    // }

    public function actionDeletefilefromproject($id, $projectId)
    {
        $file = File::findOne($id);
        if (!$file) {
            throw new NotFoundHttpException("File not found");
        }

        return $this->render('deletefilefromproject', [
                'model' => $file,
                'projectId' => $projectId,
            ]);
        // $file->delete();

        // return $this->redirect(['index', 'projectId' => $projectId]);
    }

    public function actionDeleteFileFromProjectConfermato($id, $projectId)
    {
        $file = File::findOne($id);
        if (!$file) {
            throw new NotFoundHttpException("File not found");
        }

        $file->delete();

        return $this->redirect(['index', 'projectId' => $projectId]);
    }





}
