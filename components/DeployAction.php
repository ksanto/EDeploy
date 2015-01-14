<?php
/**
 * Created by PhpStorm.
 * User: develop
 * Date: 31.12.14
 * Time: 14:06
 */
namespace app\components;

use Yii;
use yii\base\Action;
use app\models\Project;

class DeployAction extends Action
{
    /**
     * Флаг проверки доступа по токену
     * @var bool
     */
    public $checkAccess = true;

    /**
     * Флаг рендера вьюхи
     * @var bool
     */
    public $render = false;

    /**
     * Выполняем выкладку
     * @param $id
     * @param $key
     * @return bool
     */
    public function run($id, $key = '')
    {
        $result = '';
        $model = Project::findOne($id);

        if($this->checkAccess && !$model->checkToken($key))
            return false;

        $ssh = Yii::$app->sshConnector->connect(
            $model->host,
            $model->username,
            Yii::$app->getSecurity()->decryptByKey($model->password, Yii::$app->params['securityKey'])
        );
        if($ssh)
        {
            $result = Yii::$app->sshConnector->run($model->command);
            $model->applyDeployData();
        }

        if($this->render) {
            return $this->controller->render($this->id, [
                'message' => $result
            ]);
        }
        return $result;
    }
}