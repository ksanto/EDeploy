<?php
/**
 * Created by PhpStorm.
 * User: develop
 * Date: 16.12.14
 * Time: 18:40
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    /**
     * Изменение данных профиля
     * @return string
     */
    public function actionIndex()
    {
        $model = $this->findModel(Yii::$app->user->id);


        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->save(false);
        }

        // Отображаем поле пароля пустым
        $model->password = '';
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest)
            return $this->goHome();
        if(!parent::beforeAction($action))
            return false;
        return true;
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}