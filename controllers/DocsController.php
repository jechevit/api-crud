<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class DocsController extends Controller
{
    public function actions()
    {
        return [

            'index' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => Url::to(['/api'], true),
            ],
            'api' => [
                'class' => 'light\swagger\SwaggerApiAction',
                'scanDir' => [
                    Yii::getAlias('@app/controllers'),
                ],
//                'api_key' => 'test'
            ],
        ];
    }
}