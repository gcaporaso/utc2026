<?php
use hail812\adminlte\widgets;
?>
<aside class="main-sidebar">

    <section class="sidebar">

         Sidebar user panel 
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/128x128.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username:''; ?> </p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

         search form 
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
         /.search form 
       
        
        
<!--         Main Sidebar Container 
<aside class="main-sidebar sidebar-dark-primary elevation-4">
   Brand Logo 
  <a href="index3.html" class="brand-link">
    <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

   Sidebar 
  <div class="sidebar">
     Sidebar user panel (optional) 
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
    </div>

     Sidebar Menu 
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
         Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library 
        <li class="nav-item menu-open">
          <a href="#" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Starter Pages
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Active Page</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inactive Page</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Simple Link
              <span class="right badge badge-danger">New</span>
            </p>
          </a>
        </li>
      </ul>
    </nav>
     /.sidebar-menu 
  </div>
   /.sidebar 
</aside>-->

        
        
<!--        
        Menu::widget([
 *      'items' => [
 *          [
 *              'label' => 'Starter Pages',
 *              'icon' => 'tachometer-alt',
 *              'badge' => '<span class="right badge badge-info">2</span>',
 *              'items' => [
 *                  ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
 *                  ['label' => 'Inactive Page', 'iconStyle' => 'far'],
 *              ]
 *          ],
 *          ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
 *          ['label' => 'Yii2 PROVIDED', 'header' => true],
 *          ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
 *          ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
 *          ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
 *          ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
 *      ]
 * ])
        -->
        
        
        
        
        
        
        <?php echo \hail812\adminlte\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu active tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Home', 'icon' => 'home', 'url' => ['site/index']],
                    ['label' => 'Archivi', 'options' => ['class' => 'header']],
                    ['label' => 'Edilizia', 'icon' => 'tasks', 'url' => ['edilizia/index']], // ,Yii::$app->user->can('ElencoRichiedenti')
                    ['label' => 'Sismica', 'icon' => 'cogs', 'url' => ['sismica/index']],
                    ['label' => 'Richiedenti', 'icon' => 'user', 'url' => ['committenti/index']], // ,Yii::$app->user->can('ElencoRichiedenti')
                    ['label' => 'Tecnici', 'icon' => 'graduation-cap', 'url' => ['tecnici/index']], //,'visible' => Yii::$app->user->can('ElencoTecnici')
                    ['label' => 'Imprese', 'icon' => 'cubes', 'url' => ['imprese/index']], //,'visible' => Yii::$app->user->can('ElencoImprese')
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Commissioni', 'icon' => 'user-o', 'url' => '#',
                        'items' => [
                            ['label' => 'Componenti', 'icon' => 'child', 'url' => ['commissioni/componenti']],
                            ['label' => 'Commmissioni', 'icon' => 'ship', 'url' => ['commissioni/commissioni']],
                            ['label' => 'Sedute', 'icon' => 'calendar', 'url' => ['commissioni/sedute','idtipocommissione'=>1]],
//                            ['label' => 'Pareri', 'icon' => 'check', 'url' => ['commissioni/pareri']],
                        ],
                      //  'visible'=> isset(Yii::$app->user) ? Yii::$app->user->can('Gestione Utenti'):false,
                    ],
                    ['label' => 'C.d.Urbanistica', 'icon' => 'object-ungroup', 'url' => ['cdu/index']],
                    ['label' => 'Modulistica', 'icon' => 'file-word-o', 'url' => ['modulistica/index']],
                    ['label' => 'Mappa', 'icon' => 'globe', 'url' => '#',
                        'items' => [
                            ['label' => 'Mappa', 'icon' => 'users', 'url' => ['mappe/index'],],
                            ['label' => 'Gis', 'icon' => 'users', 'url' => ['gis/index'],],
                        ]
                    ],
                    [
                        'label' => 'Admin Utenti',
                        'icon' => 'user-secret',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Utenti', 'icon' => 'users', 'url' => ['/admin/user'],],
                            ['label' => 'Ruoli', 'icon' => 'hand-o-up', 'url' => ['/admin/role'],],
                            ['label' => 'Permessi', 'icon' => 'check-square', 'url' => ['/admin/permission'],],
                            ['label' => 'Assegnazioni', 'icon' => 'arrow-circle-o-down', 'url' => ['/admin/assignment'],],
                            ['label' => 'Azioni', 'icon' => 'check-square', 'url' => ['/admin/route'],],
                        ],
                        'visible'=> isset(Yii::$app->user) ? Yii::$app->user->can('Gestione Utenti'):false,
                    ],
                    ['label' => 'Posta in Arrivo', 'icon' => 'envelope-o', 'url' => ['email/index']],
                ],
            ]
        ) ?>

    </section>


</aside>
