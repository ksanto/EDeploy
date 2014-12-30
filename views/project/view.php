<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = $model->title;
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'command:ntext',
            [
                'attribute' => 'category_id',
                'value' =>  $model->getCategory()->one()->title
            ],
            [
                'attribute' => 'active_status',
                'value' =>  $model->getStatus()
            ],
            'last_deploy_date',
        ],
    ]) ?>

    <p>
        <?=Yii::t('app', 'Make a request for outside deploy:')?>
        <b>
            <?= Url::base(true).Url::to(['/api/index', 'id' => $model->id, 'key' => $model->getToken()]);?>
        </b>
    </p>
</div>
