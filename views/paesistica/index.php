<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
<!-- <script type="text/javascript">

</script> -->
<?php
$this->registerJs("
  function apply_filter() {
  $('.dynagrid-1').yiiGridView('applyFilter');
  }
  // $(document).ready(function () {
  //   var okimport = import; 
  //   var Toast = Swal.mixin({
  //       toast: true,
  //       position: 'top-end',
  //       showConfirmButton: false,
  //       timer: 3000
  //     });
  //   if (okimport) {
  //       Toast.fire({
  //         icon: 'success',
  //         title: 'Dati importati con successo.'
  //       });
  //   } else { 
  //       Toast.fire({
  //         icon: 'error',
  //         title: 'Errore importazione dati.'
  //       });
    
  //   }
  // });
", yii\web\View::POS_READY);
//use yii\grid\GridView;
//use kartik\dynagrid\DynaGrid;
//use kartik\select2\Select2;
//use kartik\tabs\TabsX;
////use kartik\grid;
////use kartik\grid\GridView;
//use yii\helpers\Arrayhelper;
//use yii\data\ActiveDataProvider;
//use yii\helpers\Html;
//use yii\helpers\Url;
//use yii\widgets\LinkPager;
//use mdm\admin\components\Configs; 
//use frontend\models\LavoriSearch;

/* @var $this yii\web\View */

//if (Yii::$app->user->isGuest) {};

$this->title = '';
//$dataProvider = new ActiveDataProvider([
//    'query' => Lavori::find(),
//    'pagination' => [
//        'pageSize' => 20,
//    ],
//]);
//echo GridView::widget([
//    'dataProvider' => $dataProvider,
//]);

?>
<div class="site-index">

    <!--<div class="jumbotron">
        <h1>Ufficio Tecnico Comunale</h1>

        <p class="lead">Applicazioni Disponibili</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>-->

      
    
    
<!--<h1>ARCHIVIO PRATICHE EDILIZIE</h1>-->
    <!--<p>
        
 <div class="row">
  <?php //include('_leftmenuedilizia.php'); ?>      
    </p>-->

<!--  <div class="col-sm-10 right_col">-->

<?php

include('_paesistica.php');         

?>



    
<!--//LinkPager::widget(['pagination' => $pagination]) -->

    </div>
</div>

