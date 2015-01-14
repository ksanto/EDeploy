<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'index' => 'app\components\DeployAction'
        ];
    }
}
