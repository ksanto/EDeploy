<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Project;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * Выполняем выкладку http запросом
     *
     * @param $id
     * @param $key
     * @return bool|string
     */
    public function actionIndex($id, $key)
    {
        $model = Project::findOne($id);
        if($model->checkToken($key))
        {
            $result = '';
            $ssh = Yii::$app->sshConnector->connect(
                $model->host,
                $model->username,
                Yii::$app->getSecurity()->decryptByKey($model->password, $model->key)
            );
            if($ssh)
            {
                $result = Yii::$app->sshConnector->run($model->command);
                $model->setDeployDate();
            }
        }
        return false;
    }

}
