<?php
use app\models\Profilo;
use yii\helpers\Url;
use yii\helpers\Html;

$_sidebarUserId   = Yii::$app->user->isGuest ? null : Yii::$app->user->id;
$_sidebarProfilo  = $_sidebarUserId ? Profilo::findOne(['user_id' => $_sidebarUserId]) : null;
$_sidebarName     = $_sidebarProfilo ? $_sidebarProfilo->getDisplayName() : '';
if (!$_sidebarName && !Yii::$app->user->isGuest) {
    $_sidebarName = Yii::$app->user->identity->username;
}
$_avatarUrl = Url::to(['/profilo/avatar']);
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-2 pb-3 mb-3 d-flex align-items-center" style="background:transparent !important;border-bottom:1px solid #4f5962;">
            <div class="image">
                <a href="<?= Url::to(['/profilo/index']) ?>">
                  <img src="<?= Html::encode($_avatarUrl) ?>" class="img-circle elevation-2"
                       style="width:35px;height:35px;object-fit:cover;" alt="Avatar">
                </a>
            </div>
            <div style="padding-left:10px;overflow:hidden;">
              <a href="<?= Url::to(['/profilo/index']) ?>"
                 class="user-info-name"
                 style="color:#c2c7d0;font-size:12px;font-weight:500;white-space:nowrap;overflow:hidden;display:block;text-decoration:none;">
                <?= Html::encode($_sidebarName) ?>
              </a>
              <?php if ($_sidebarProfilo && $_sidebarProfilo->ruolo): ?>
              <span style="color:#6c757d;font-size:10px;white-space:nowrap;overflow:hidden;display:block;">
                <?= Html::encode($_sidebarProfilo->ruolo) ?>
              </span>
              <?php endif; ?>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
<!--        <ul class="sidebar-menu" data-widget="tree">-->
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"  data-accordion="false">
                <li class="nav-item">
                  <a href="<?= Url::to(['/site/index']) ?>" class="nav-link">
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
                    <i class="nav-icon fas fa-archive text-warning"></i>
                    <p>
                      ARCHIVI
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview nav-child-indent">
                    <li class="nav-item  pl-2">
                      <a href="<?= Url::to(['/committenti/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-user text-info"></i>
                        <p>Richiedenti</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-2">
                      <a href="<?= Url::to(['/tecnici/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-graduation-cap text-info"></i>
                        <p>Tecnici</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-2">
                      <a href="<?= Url::to(['/imprese/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-cubes text-info"></i>
                        <p>Imprese</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-2">
                      <a href="<?= Url::to(['/modulistica/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-alt text-info"></i>
                        <p>Modulistica</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="<?= Url::to(['/edilizia/index']) ?>" class="nav-link">
                    <i class="nav-icon fas fa-tasks text-warning"></i>
                    <p>
                      EDILIZIA
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= Url::to(['/paesistica/index']) ?>" class="nav-link">
                    <i class="nav-icon fas fa-images text-warning"></i>
                    <p>
                      PAESISTICA
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= Url::to(['/sismica/index']) ?>" class="nav-link">
                    <i class="nav-icon fas fa-cogs text-warning"></i>
                    <p>
                      SISMICA
                    </p>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users text-warning"></i>
                    <p>
                      COMMISSIONI
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview nav-child-indent">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/commissioni/componenti']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-child text-info"></i>
                        <p>Componenti</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/commissioni/commissioni']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-user-cog text-info"></i>
                        <p>Commissioni</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/commissioni/sedute','idtipocommissione'=>1]) ?>" class="nav-link">
                        <i class="nav-icon fas fa-calendar text-info"></i>
                        <p>Sedute</p>
                      </a>
                    </li>
<!--                    <li class="nav-item  pl-1">
                      <a href="<?php // Url::to(['/Commissioni/pareri']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-check text-info"></i>
                        <p>Pareri</p>
                      </a>
                    </li>-->
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-file-contract text-warning"></i>
                    <p>
                      URBANISTICA
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/cdu/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-word text-info"></i>
                        <p>Certificati</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-globe text-warning"></i>
                    <p>
                      MAPPE
                      <i class="right fas fa-angle-left"></i>
        <!--              <span class="right badge badge-danger">New</span>-->
                    </p>
                  </a>
                    <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/mappe/dati-censuari']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-word text-info"></i>
                        <p>Aggiorna Dati Censuari</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/mappe/aggiorna-mappe']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-word text-info"></i>
                        <p>Aggiorna Mappe GeoJson</p>
                      </a>
                    </li>
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/mappe/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-word text-info"></i>
                        <p>Mappe</p>
                      </a>
                    </li>
                  </ul>
                    <!-- <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/gis/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-word text-info"></i>
                        <p>Gis</p>
                      </a>
                    </li>
                  </ul>
                    <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/lizmap/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-word text-info"></i>
                        <p>Lizmap</p>
                      </a>
                    </li>
                  </ul> -->
                </li>
                <!-- <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-comment text-warning"></i>
                    <p>
                      AI-CHAT
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?php // Url::to(['/chat/qchat']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-server text-info"></i>
                        <p>info da Archivio</p>
                      </a>
                    </li>
                  </ul>
                  <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?php // Url::to(['/chat/aichat']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-comments text-info"></i>
                        <p>Chat UTC</p>
                      </a>
                    </li>
                  </ul>  
                </li> -->
                
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-drafting-compass text-warning"></i>
                    <p>
                      PROGETTI GIS
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview nav-child-indent">
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/progetti-gis/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-layer-group text-info"></i>
                        <p>Elenco Progetti</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/progetti-gis/create']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-plus text-info"></i>
                        <p>Nuovo Progetto</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-landmark text-warning"></i>
                    <p>
                      IMU
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview nav-child-indent">
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/imu/calcolo']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-calculator text-info"></i>
                        <p>Calcolo</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/imu/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-percent text-info"></i>
                        <p>Aliquote</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/imu/aree-edificabili']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-map-marked-alt text-info"></i>
                        <p>Aree Edificabili</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/imu/f24-import']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-import text-info"></i>
                        <p>Forniture F24</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/imu/ici-import']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-exchange-alt text-warning"></i>
                        <p>Variazioni Catastali</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cog text-warning"></i>
                    <p>
                      UTILITA
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/db-backup/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-database text-info"></i>
                        <p>Backup</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="<?= Url::to(['/profilo/index']) ?>" class="nav-link">
                    <i class="nav-icon fas fa-user-circle text-warning"></i>
                    <p>IL MIO PROFILO</p>
                  </a>
                </li>
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('Gestione Utenti')): ?>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users-cog text-warning"></i>
                    <p>
                      CONFIGURAZIONE
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview nav-child-indent">
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/admin/user']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-users text-info"></i>
                        <p>Utenti</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/admin/assignment']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-user-tag text-info"></i>
                        <p>Assegnazioni</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/admin/role']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-shield-alt text-info"></i>
                        <p>Ruoli</p>
                      </a>
                    </li>
                    <li class="nav-item pl-1">
                      <a href="<?= Url::to(['/admin/permission']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-key text-info"></i>
                        <p>Permessi</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <?php endif; ?>

            
                <?php if (!Yii::$app->user->isGuest): ?>
                <li class="nav-item" style="margin-top:auto;">
                  <?= Html::beginForm(['/admin/user/logout'], 'post', ['id' => 'sidebar-logout-form']) ?>
                  <button type="submit" class="nav-link btn btn-link w-100 text-left"
                          style="border:none;background:none;padding:.5rem 1rem;">
                    <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                    <p style="color:#c2c7d0;">ESCI</p>
                  </button>
                  <?= Html::endForm() ?>
                </li>
                <?php endif; ?>

    </ul>




            <?php
//            echo \hail812\adminlte\widgets\Menu::widget([
////                'items' => [
////                    [
////                        'label' => 'Starter Pages',
////                        'icon' => 'tachometer-alt',
////                        'badge' => '<span class="right badge badge-info">2</span>',
////                        'items' => [
////                            ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
////                            ['label' => 'Inactive Page', 'iconStyle' => 'far'],
////                        ]
////                    ],
////                    ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
////                    ['label' => 'Yii2 PROVIDED', 'header' => true],
////                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
////                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
////                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
////                    ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
////                    ['label' => 'Level1'],
////                    [
////                        'label' => 'Level1',
////                        'items' => [
////                            ['label' => 'Level2', 'iconStyle' => 'far'],
////                            [
////                                'label' => 'Level2',
////                                'iconStyle' => 'far',
////                                'items' => [
////                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
////                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
////                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
////                                ]
////                            ],
////                            ['label' => 'Level2', 'iconStyle' => 'far']
////                        ]
////                    ],
////                    ['label' => 'Level1'],
////                    ['label' => 'LABELS', 'header' => true],
////                    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
////                    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
////                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconStyle' => 'far'],
////                ],
//                
//                'items' => [
//                    ['label' => 'Home', 'icon' => 'home', 'url' => ['site/index']],
//                    //['label' => 'Archivi', 'icon' => 'archive', 'header' => true, 'url' => '#',
//                    ['label' => 'Archivi', 'iconClass' => 'nav-icon fas fa-archive text-warning',
//                     'items' => [
//                        ['label' => 'Richiedenti', 'iconClass' => 'nav-icon fas fa-user text-info', 'url' => ['committenti/index']], // ,Yii::$app->user->can('ElencoRichiedenti')
//                        ['label' => 'Tecnici', 'iconClass' => 'nav-icon fas fa-graduation-cap text-info', 'url' => ['tecnici/index']], //,'visible' => Yii::$app->user->can('ElencoTecnici')
//                        ['label' => 'Imprese', 'iconClass' => 'nav-icon fas fa-cubes text-info', 'url' => ['imprese/index']], //,'visible' => Yii::$app->user->can('ElencoImprese')
//                        ['label' => 'Modulistica', 'iconClass' => 'nav-icon fas fa-file-alt text-info', 'url' => ['modulistica/index']],
//                        ],
//                    ],    
//                    ['label' => 'Edilizia', 'iconClass' => 'nav-icon fas fa-tasks text-warning', 'url' => ['edilizia/index']], // ,Yii::$app->user->can('ElencoRichiedenti')
//                    ['label' => 'Sismica', 'icon' => 'cogs', 'url' => ['sismica/index']],
//                    ['label' => 'Paesistica', 'icon' => 'images', 'url' => ['paesistica/index']],
//                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
//                    ['label' => 'Commissioni', 'icon' => 'users', 
//                        'items' => [
//                            ['label' => 'Componenti', 'icon' => 'child', 'url' => ['commissioni/componenti']],
//                            ['label' => 'Commmissioni', 'icon' => 'ship', 'url' => ['commissioni/commissioni']],
//                            ['label' => 'Sedute', 'icon' => 'calendar', 'url' => ['commissioni/sedute','idtipocommissione'=>1]],
//                            ['label' => 'Pareri', 'icon' => 'check', 'url' => ['commissioni/pareri']],
//                        ],
//                      //  'visible'=> isset(Yii::$app->user) ? Yii::$app->user->can('Gestione Utenti'):false,
//                    ],
//                    ['label' => 'C.d.Urbanistica', 'icon' => 'object-ungroup', 'url' => ['cdu/index']],
//                    
//                    ['label' => 'Mappa', 'icon' => 'globe', 'url' => ['mappe/index']],
//                    ['label' => 'Admin Utenti', 'icon' => 'user-secret', 'url' => '#',
//                        'items' => [
//                            ['label' => 'Utenti', 'icon' => 'users', 'url' => ['/admin/user'],],
//                            ['label' => 'Ruoli', 'icon' => 'hand-o-up', 'url' => ['/admin/role'],],
//                            ['label' => 'Permessi', 'icon' => 'check-square', 'url' => ['/admin/permission'],],
//                            ['label' => 'Assegnazioni', 'icon' => 'arrow-circle-o-down', 'url' => ['/admin/assignment'],],
//                            ['label' => 'Azioni', 'icon' => 'check-square', 'url' => ['/admin/route'],],
//                        ],
//                        'visible'=> isset(Yii::$app->user) ? Yii::$app->user->can('Gestione Utenti'):false,
//                    ],
//                    ['label' => 'Posta Elettronica', 'iconClass' => 'nav-icon fas fa-envelope text-info', 'url' => ['email/index']],
//                ],
//                
//                
//                
//                
//                
//            ]);
//            ?>
        </nav>
<!--        </ul>-->
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>