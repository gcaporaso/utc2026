<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;


class SamlController extends Controller {

    // Remove CSRF protection
    public $enableCsrfValidation = false;

    public function actions() {
        return [
            'login' => [
                'class' => 'asasmoyo\yii2saml\actions\LoginAction',
                'returnTo' => Yii::$app->user->returnUrl
            ],
            'acs' => [
                'class' => 'asasmoyo\yii2saml\actions\AcsAction',
                'successCallback' => [$this, 'callback'],
                'successUrl' => Url::to('site/welcome'),
            ],
            'metadata' => [
                'class' => 'asasmoyo\yii2saml\actions\MetadataAction'
            ],
            'logout' => [
                'class' => 'asasmoyo\yii2saml\actions\LogoutAction',
                'returnTo' => Url::to('site/bye'),
                'parameters' => [],
                'nameId' => Yii::$app->session->get('nameId'),
                'sessionIndex' => Yii::$app->session->get('sessionIndex'),
                'stay' => false,
                'nameIdFormat' => null,
                'nameIdNameQualifier' => Yii::$app->session->get('nameIdNameQualifier'),
                'nameIdSPNameQualifier' => Yii::$app->session->get('nameIdSPNameQualifier'),
                'logoutIdP' => false, // if you don't want to logout on idp
            ],
            'sls' => [
                'class' => 'asasmoyo\yii2saml\actions\SlsAction',
                'successUrl' => Url::to('site/bye'),
                'logoutIdP' => false, // if you don't want to logout on idp
            ]
        ];
        
        
        
        
    }
    /**
     * @param array $param has 'attributes', 'nameId' , 'sessionIndex', 'nameIdNameQualifier' and 'nameIdSPNameQualifier' from response
     */
    public function callback($param) {
        // do something
        //
        // if (isset($_POST['RelayState'])) {
        // $_POST['RelayState'] - should be returnUrl from login action
        // }
    }
    
    
    
    
    

}