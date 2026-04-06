<?php

Yii::setAlias('@webpub', dirname(__DIR__) . '/web/');
Yii::setAlias('@webcod', dirname(__DIR__));

//'@allegati'=>'/var/www/ufficiotecnico/allegati/',
//        '@pratiche'=>'/var/www/ufficiotecnico/web/pratiche/',
//        '@sismica'=>'/var/www/ufficiotecnico/web/sismica/',
//        '@commissioni'=>'/var/www/ufficiotecnico/web/commissioni/',
//Yii::setAlias('@allegati', '@webcod/allegati/');
//Yii::setAlias('@pratiche', '@webpub/pratiche/');
Yii::setAlias('@pathallegati', '@webpub/allegati/');
Yii::setAlias('@pathedilizia', '@webpub/pratiche/');
Yii::setAlias('@pathsismica', '@webpub/sismica/');
Yii::setAlias('@modulistica', '@webpub/modulistica/');
Yii::setAlias('@commissioni', '@webpub/commissioni/');
Yii::setAlias('@cdu', '@webpub/cdu/');