<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deploy';
?>
<div class="site-index">
    <div class="body-content">
        <? foreach ($dataProvider->getModels() as $model): ?>
            <div class="row">
                <h1><?= Html::encode($model->title) ?></h1>
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getProjects()]),
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        'id',
                        [
                            'attribute' => 'title',
                            'headerOptions' => ['class' => 'col-xs-2'],
                            'format' => 'html',
                            'value' => function($model) {
                                    return Html::tag('strong', $model->title);
                                }
                        ],
                        ['attribute' => 'category_id',
                            'value' => function ($model) {
                                    return $model->getCategory()->one()->title;
                                },
                            'headerOptions' => ['class' => 'col-xs-2']
                        ],
                        ['attribute' => 'active_status',
                            'value' => function ($model) {
                                    return $model->getStatus();
                                }
                        ],
                        'last_deploy_date',

                        [
                            'class' => \yii\grid\ActionColumn::className(),
                            'buttons' => [
                                'deploy' => function ($url, $model) {
                                        $url = Yii::$app->getUrlManager()->createUrl(['site/deploy', 'id' => $model->id]);
                                        return \yii\helpers\Html::a(
                                            'Deploy',
                                            $url,
                                            [
                                                'class' => 'btn btn-danger'
                                            ]
                                        );
                                    }
                            ],
                            'template' => '{deploy}',
                            'headerOptions' => ['class' => 'col-xs-1']
                        ]

                    ],
                ]); ?>
            </div>
        <? endforeach; ?>
    </div>
</div>