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
                'command:ntext',
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

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
