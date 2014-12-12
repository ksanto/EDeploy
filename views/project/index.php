<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Project',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            // 'last_deploy_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
