<?php
use yii\helpers\Url;
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
                    <i class="nav-icon fas fa-cog text-warning"></i>
                    <p>
                      BIM
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/project/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-database text-info"></i>
                        <p>Progetti</p>
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
                      <a href="<?= Url::to(['/db-manager/default/index']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-database text-info"></i>
                        <p>Backup</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users text-warning"></i>
                    <p>
                      CONFIGURAZIONE
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/admin/user']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-user text-info"></i>
                        <p>Utenti</p>
                      </a>
                    </li>
                  </ul>
                    <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/admin/role']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-user-times text-info"></i>
                        <p>Ruoli</p>
                      </a>
                    </li>
                  </ul>
                    <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/admin/permission']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-user-secret text-info"></i>
                        <p>Permessi</p>
                      </a>
                    </li>
                    </ul><!-- comment -->
                    <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/admin/assignment']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-user-plus text-info"></i>
                        <p>Assegnazioni</p>
                      </a>
                    </li>
                  </ul>
                  <ul class="nav nav-treeview">
                    <li class="nav-item  pl-1">
                      <a href="<?= Url::to(['/admin/route']) ?>" class="nav-link">
                        <i class="nav-icon fas fa-th text-info"></i>
                        <p>Azioni</p>
                      </a>
                    </li>
                  </ul>
                </li>

            
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