<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deploy';
?>
<div class="site-index">

    <div class="body-content">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title',
                ['attribute' => 'category_id',
                    'value' => function ($model) {
                            return $model->getCategory()->one()->title;
                        }
                ],
                ['attribute' => 'active_status',
                    'value' => function ($model) {
                            return $model->getStatus();
                        }
                ],
                 'last_deploy_date',

                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'buttons'=>[
                        'deploy'=>function ($url, $model) {
                                $url=Yii::$app->getUrlManager()->createUrl(['project/deploy','id'=>$model->id]);
                                return \yii\helpers\Html::a(
                                    'Deploy',
                                    $url,
                                    [
                                        'class' => 'btn btn-danger'
                                    ]
                                );
                            }
                    ],
                    'template'=>'{deploy}',
                ]

            ],
        ]); ?>
    </div>
</div>
