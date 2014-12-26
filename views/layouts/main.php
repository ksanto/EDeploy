<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'EDeploy',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            if (!Yii::$app->user->isGuest) {
                $nav_config['options'] = ['class' => 'navbar-nav navbar-right'];
                $nav_config['items'][] = ['label' => 'Home', 'url' => ['/site/index']];
                $nav_config['items'][] = ['label' => 'Categories', 'url' => ['/category/index']];
                $nav_config['items'][] = ['label' => 'Projects', 'url' => ['/project/index']];

                // админу покажем профили пользователей
                if(Yii::$app->user->identity->is_admin)
                    $nav_config['items'][] = ['label' => 'Users', 'url' => ['/user/index']];

                $nav_config['items'][] = ['label' => 'Profile', 'url' => ['/user/update', 'id' => Yii::$app->user->identity->id]];
                $nav_config['items'][] = ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                                            'url' => ['/site/logout'],
                                            'linkOptions' => ['data-method' => 'post']
                ];

                echo Nav::widget($nav_config);
            }
            NavBar::end();
        ?>

        <div class="container">
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; EDeploy <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
