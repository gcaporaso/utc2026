<?php
use yii\helpers\Url;
// recupero Id del progetto corrente dalla variabile di sessione
$projectId = Yii::$app->session->get('projid');
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
<!--    <a href="index3.html" class="brand-link">
        <img src="<?php //$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>-->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-2 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username:'**'; ?>Giuseppe Caporaso</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
<!--        <ul class="sidebar-menu" data-widget="tree">-->
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"  data-accordion="false">
                <li class="nav-item">
                  <a href="<?= Url::to(['/project/index', 'projectId' => $projectId]) ?>" class="nav-link">
                    <i class="nav-icon fas fa-home text-warning"></i>
                    <p>
                      HOME
        <!--              <span class="right badge badge-danger">New</span>-->
                    </p>
                  </a>
                </li>
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item"> 
                  <a href="#" class="nav-link">
                    <img src="/img/setting-94_2.png" style="width:25px" />
                    <!-- <i class="nav-icon fas fa-archive text-warning"></i> -->
                    <p>
                      CONFIGURAZIONE
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview nav-child-indent">
                    <li class="nav-item  pl-2">
                      <a href="<?= Url::to(['/raci/tasks', 'projectId' => $projectId]) ?>" class="nav-link">
                        <i class="nav-icon fas fa-graduation-cap text-info"></i>
                        <p>Generali</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-2">
                      <a href="<?= Url::to(['/raci/matrix', 'projectId' => $projectId]) ?>" class="nav-link">
                        <i class="nav-icon fas fa-graduation-cap text-info"></i>
                        <p>Attività</p>
                      </a>
                    </li>

                  </ul>  
                </li>    
                <li class="nav-item">
                  <a href="<?= Url::to(['/project/index']) ?>" class="nav-link">
                    <i class="nav-icon fas fa-user text-info"></i>
                    <p>Progetti</p>
                  </a>
                </li>
                  
               
    </ul>
            
            
            
            
        </nav>
<!--        </ul>-->
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>