<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    
    <ul class="navbar-nav">
        
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link"  href="index.php?r=project/index" role="button">
                <img src="/img/back-48_1.png" style="width:32px" />
                 <i class="fas fa-search"></i> 
            </a>
        </li> -->

    
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <h1><?php //= $Name ?></h1>
        </li> -->
        <!-- <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li> -->
        <li class="nav-item d-none d-sm-inline-block">
            <h2 style="margin:25px 0px 0 25px">Progetti</h2>
            <!-- <a href="#" class="nav-link">Progetti</a> -->
        </li>
      
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar -->
         <li class="nav-item">
            <!-- <a class="nav-link" href="index.php?r=folder/create&projectId=<?php // $pId ?>" role="button"> -->
                <a class="nav-link" data-method="post" id="btnNewFolder" href="index.php?r=project/create" role="button">
                <img src="/img/create-94_2.png" title="crea un nuovo progetto" style="width:32px" />
                <!-- <i class="fas fa-search"></i> -->
            </a>
        </li>
        <li class="nav-item">
            <!-- <a class="nav-link" href="index.php?r=folder/create&projectId=<?php // $pId ?>" role="button"> -->
                <a class="nav-link" data-method="post" id="btnNewFolder" href="index.php?r=site/logout" role="button">
                <img src="/img/logout-48_1.png" title="esci dall'applicazione" style="width:32px" />
                <!-- <i class="fas fa-search"></i> -->
            </a>
        </li>

       
    </ul>
</nav>
<!-- /.navbar -->

<?php
