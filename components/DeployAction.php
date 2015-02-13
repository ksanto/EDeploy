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
use app\models\User;

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
     * @param int $id
     * @param string $key
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

        $this->send($model, $result);

        if($this->render) {
            return $this->controller->render($this->id, [
                'message' => $result
            ]);
        }
        return $result;
    }

    protected function send($model, $msg)
    {
        $users = User::find()->joinWith('permission')->where(
            'user.email IS NOT NULL AND (user.is_admin='.User::ADMIN.' OR deploy_right.project_id='.$model->id.')'
        )->all();

        $messages = [];
        foreach($users as $user) {
            $messages[] = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($user->email)
                ->setSubject($model->title.'('.$model->category->title.')')
                ->setTextBody($msg);
        }
        Yii::$app->mailer->sendMultiple($messages);
    }
}