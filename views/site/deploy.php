<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = 'Deploy';
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::a('Ok', '/', ['class' => 'btn btn-primary']); ?>
    </div>
</div>