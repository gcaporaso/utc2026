<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//use  yii\web\View;
//app\assets\AdminLTEAsset::register($this);
use app\models\Edilizia;


//$this->registerJsFile('jquery-3.5.0.min.js');

////$statistiche= Edilizia::find()
////                ->Select('count(id_titolo) as numero, id_titolo')
////                ->Where(['Between','edilizia.DataProtocollo', date('Y').'-01-01',date('Y').'-12-31'])
////                ->GroupBy(['id_titolo'])
////                ->asArray()
////                ->all();
////
//Yii::$app->formatter->locale = 'et-EE';
            $result=[0,0,0,0,0,0,0,0];
            if (isset($statistiche)) {
                //$altro=0;
                foreach ($statistiche as $elem) {
                    if ($elem['id_titolo']<8) {
                    $result[$elem['id_titolo']]=$elem['numero'];    
                    } else {
                    $result[7]=$result[7]+$elem['numero'];    
                    }
                    
                    //print_r($elem);
                }
            }
//$el= json_decode($media);

$numPratiche = Edilizia::find()->count();
$nRichiedenti = app\models\Committenti::find()->count();
$nTecnici = app\models\Tecnici::find()->count();
$nImprese = app\models\Imprese::find()->count();

//echo var_dump($pagati);
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="margin-left:10px!important">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        RIEPILOGO DATI ANNO <?= (date('Y')-1); ?>
<!--        <small>Pagina inziale</small>-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="edilizia/index"><i class="fa fa-dashboard"></i>Pratiche Edilizie</a></li>
<!--        <li class="active">Widgets</li>-->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-industry"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Permessi di Costruire</span>
              <span class="info-box-number"><?php echo $result[4]; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fas fa-building"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SuperSCIA</span>
              <span class="info-box-number"><?php echo $result[3]; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fas fa-hotel"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SCIA</span>
              <span class="info-box-number"><?php echo $result[2]; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fas fa-city"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">CILA</span>
              <span class="info-box-number"><?php echo $result[1]; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fas fa-landmark"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SCA</span>
              <span class="info-box-number"><?php echo $result[5]; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fas fa-hospital"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Aut.ni Sismiche</span>
              <span class="info-box-number">2</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- =========================================================== -->

      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Tempi Medi Procedimento (PC)</span>
              <span class="info-box-number"><?= $media1 ?> Giorni</span>

              <div class="progress">
                <div class="progress-bar" style="width: -10%"></div>
              </div>
                  <span class="progress-description">
                    Media riferita agli ultimi tre anni
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-thumbs-o-up"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Tempi medi Procedimento (Sismica)</span>
              <span class="info-box-number"><?= $media2 ?> Giorni</span>

              <div class="progress">
                <div class="progress-bar" style="width: -8%"></div>
              </div>
                  <span class="progress-description">
                    Media riferita agli ultimi cinque anni
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Oneri Concessori Deliberati</span>
              <span class="info-box-number">€. <?= Yii::$app->formatter->asDecimal($oneri) ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    -5% Rispetto all'anno scorso
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-comments-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Oneri Concessori Incassati</span>
              <span class="info-box-number">€. <?= Yii::$app->formatter->asDecimal($pagati); ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 5%"></div>
              </div>
                  <span class="progress-description">
                    -5% Rispetto all'anno scorso
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- =========================================================== -->

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $numPratiche ?></h3>

              <p>Pratiche Edilizie in Archivio</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="index.php?r=edilizia/index" class="small-box-footer">
              Visualizza <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $nRichiedenti ?></h3>

              <p>Numero Soggetti Richiedenti in Archivio</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="index.php?r=committenti/index" class="small-box-footer">
              Visualizza <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $nTecnici ?></h3>

              <p>Numero Tecnici in Archivio</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="index.php?r=tecnici/index" class="small-box-footer">
              Visualizza <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $nImprese ?></h3>

              <p>Numero Imprese in Archivio</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="index.php?r=imprese/index" class="small-box-footer">
              Visualizza <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <!-- =========================================================== -->

    
        
<!--        
 Content Wrapper. Contains page content 
  
     Content Header (Page header) 
    <section class="content-header">
      <h1>
        GRAFICI
        <small>Riepilogo Pratiche Edilizie</small>
      </h1>
      
    </section>

     Main content 
    <section class="content">
      <div class="row">
        <div class="col-md-6">
           AREA CHART 
          
           DONUT CHART 
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Grafico a Torta</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChart" style="height:250px"></canvas>
            </div>
             /.box-body 
          </div>
           /.box 

        </div>
         /.col (LEFT) 
        <div class="col-md-6">
           LINE CHART 
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Grafico a Linee</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:250px"></canvas>
              </div>
            </div>
             /.box-body 
          </div>
           /.box 

        </div>
         /.col (RIGHT) 
      </div>
       /.row 

    </section>-->
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  

  <!-- ./wrapper -->
