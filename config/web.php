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
            'layout' => null, //'left-menu', // defaults to null, using the application's layout without the menu
                                     // other available values are 'right-menu' and 'top-menu'
            'mainLayout' => '@app/views/layouts/main.php',
            'controllerMap' => [
                 'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'mdm\admin\models\User',
                    //'idField' => 'User_id'
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
                //'admin/*', // add or remove allowed actions to this list
                // The actions listed here will be allowed to everyone including guests.
                // So, 'admin/*' should not appear here in the production, of course.
                // But in the earlier stages of your development, you may probably want to
                // add a lot of actions here until you finally completed setting up rbac,
                // otherwise you may not even take a first step.
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'yZYcNfcfmO91YDd2daj9d7TbyDf73uRE',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
