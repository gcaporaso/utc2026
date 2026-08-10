<?php
require_once(__DIR__.'/functions.php');
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'utcbim',
    'name'=>'utc-gis-bim',
    'Version'=>'1.10',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
    ],
    'language' => 'it-IT',
    'modules' => [
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
            
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
        // other module settings, refer detailed documentation
          
        ],    
        // 'filepond' => [
        //    'class' => '\vkabachenko\filepond\Module',
        //    'basePath'=>'/var/www/ufficiotecnico/allegati/'
        // ],
            // other module settings
            
        'gridview'=> [
            'class'=>'\kartik\grid\Module',
                   // 'downloadAction' => 'gridview/export/download,
                    
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
//                    // format settings for displaying each date attribute (ICU format example)
                'displaySettings' => [
                        'date' => 'php:d-m-Y',
                        'time' => 'php:H:i:s',
                        'datetime' => 'php:d-m-Y H:m:s', 
                    ],
//        
//                    // format settings for saving each date attribute (PHP format example)
                'saveSettings' => [
                    'date' => 'php:Y-m-d', // saves as unix timestamp
                    'time' => 'php:H:i:s',
                    'datetime' => 'php:Y-m-s H:m:s', 
//                        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
                    ],
//                    // set your timezone for date saved to db
                'saveTimezone' => 'UTC',
                'autoWidget' => true,
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['192.168.1.*', '127.0.0.1', '::1'],
            //'traceLine' => '<a href="phpstorm://open?url={file}&line={line}">{file}:{line}</a>',
            'panels' => [
                'db' => [
                'class' => 'yii\debug\panels\DbPanel',
                'defaultOrder' => [
                    'seq' => SORT_ASC
                    ],
                'defaultFilter' => [
                    'type' => 'SELECT'
                    ]
                ],
            ],
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => null,
            'mainLayout' => '@app/views/layouts/main.php',
            'viewPath' => '@app/views/admin',
            'controllerMap' => [
                'user' => 'app\controllers\AdminUserController',
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'mdm\admin\models\User',
                ]
            ],
            'menus' => [
                'assignment' => [
                    'label' => 'Assegnazioni' // change label
                    ],
                'route' => null, // disable menu
                'menu' => null //'left-menu',
            ],
        ],
     ],    
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'defaultRoles' => ['Cittadino'],
        ],
        
        'as access' => [
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
                'profilo/avatar',       // avatar accessibile senza login (img src nella sidebar)
                'admin/user/login',
                'admin/user/logout',
                'admin/user/signup',    // registrazione aperta a tutti
                'admin/user/request-password-reset',
                'admin/user/reset-password',
                'site/index',
                'site/error',
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'yZYcNfcfmO91YDd2daj9d7TbyDf73uRE',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'assetManager' => [
            'bundles' => [
                // bootstrap-dialog.js usa $.fn.modal (Bootstrap 4 plugin);
                // dichiariamo esplicitamente la dipendenza per garantire
                // che Bootstrap venga caricato prima del bundle dialog.
                'kartik\dialog\DialogBootstrapAsset' => [
                    'depends' => [
                        'kartik\dialog\DialogAsset',
                        'yii\bootstrap4\BootstrapPluginAsset',
                    ],
                ],
            ],
        ],

        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
//                    'sourceLanguage' => 'it-IT'
                ],
                'kvgrid' => [
//                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@app/messages',
                    'class'=>'yii\i18n\PhpMessageSource',
                    'basePath'=>'@vendor/kartik-v/yii2-dynagrid/messages',
                    'forceTranslation'=>true,
                    'sourceLanguage' => 'it-IT'
                      ],
                ],
                
 
        ],
        'user' => [
            'identityClass' => 'mdm\admin\models\User', 
            'enableAutoLogin' => true,
            'loginUrl' => ['admin/user/login'],
            'autoRenewCookie' => true,
            'authTimeout' => 21600, // Cancella session dopo 6 ore 
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info','trace'],
                ],
            ],
        ],
        'db' => $db,
        'view' => [
         'theme' => [
             'pathMap' => [
                '@app/views' =>'@app/views'  //'@vendor/hail812/yii2-adminlte3/src/views'
             ],
         ],
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
    
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    //Tool bar di debug
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['192.168.1.*', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['192.168.1.*', '::1'],
    ];
}

return $config;
