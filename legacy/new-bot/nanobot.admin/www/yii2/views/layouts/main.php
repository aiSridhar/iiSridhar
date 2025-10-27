<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);


//if (Yii::$app->user->isGuest) {

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav nav-pills'], // navbar-right
	'encodeLabels' => false,
        'items' => [
            ['label' => 'Home', 'url' => ['/admin/site/index']],


        [
            'label' => '<img src=/admin/imgs/f/script-block.png> scripts',
            'items' => [
                 ['label' => '<img src=/admin/imgs/f/script-block.png> scripts', 'url' => '/admin/scripts/index'],
                 ['label' => '<img src=/admin/imgs/f/script-text.png> classes', 'url' => '/admin/classes/index'],
            ],
        ],

        [
            'label' => '<img src=/admin/imgs/f/balloon.png> messages',
            'items' => [
                 ['label' => '<img src=/admin/imgs/f/balloon.png> all', 'url' => '/admin/messages_all/index'],
                 '<li class="divider"></li>',
                 ['label' => '<img src=/admin/imgs/f/cross-white.png> noclass', 'url' => '/admin/messages-in-group/index?MessagesInGroupSearch%5Bclass_id%5D=-1'],
                 '<li class="divider"></li>',
                ['label' => '<img src=/admin/imgs/f/balloons.png> dialogs', 'url' => '/admin/messages-in-group/index?MessagesInGroupSearch%5Bclass_id%5D=-1','options' => ['class' => 'disabled']],
                '<li class="divider"></li>',
                ['label' => '<img src=/admin/imgs/f/navigation-270-white.png> in', 'url' => '/admin/messages-in-group/index'],
                ['label' => '<img src=/admin/imgs/f/navigation-090.png> out', 'url' => '/admin/messages-out/index'],
            ],
        ],



            [
                'label' => '<img src=/admin/imgs/f/robot.png> accounts',
                'items' => [
                    ['label' => '<img src=/admin/imgs/f/robot.png> accounts', 'url' => '/admin/lists/index','options' => ['class' => 'disabled']],
                ],
            ],



        [
            'label' => '<img src=/admin/imgs/f/tables.png> lists',
            'items' => [
                 ['label' => '<img src=/admin/imgs/f/tables.png> lists', 'url' => '/admin/lists/index','options' => ['class' => 'disabled']],
            ],
        ],


        [
            'label' => '<img src=/admin/imgs/f/user-female.png> experts',
            'items' => [
                 ['label' => '<img src=/admin/imgs/f/user-female.png> mass_class', 'url' => '/stat_users/index','options' => ['class' => 'disabled']],
            ],
        ],



        [
            'label' => '<img src=/admin/imgs/f/chart.png> stat',
            'items' => [
                 ['label' => 'users', 'url' => '/stat_users/index','options' => ['class' => 'disabled']],
                 ['label' => 'outbox', 'url' => '/messages_out/index','options' => ['class' => 'disabled']],
//                 '<li class="divider"></li>',
//                 '<li class="dropdown-header">Dropdown Header</li>',
            ],
        ],


            //['label' => 'About', 'url' => ['/site/about']],
            //['label' => 'Contact', 'url' => ['/site/contact']],
//	    ['label' => 'Scripts', 'url' => ['/scripts/index']],
//            ['label' => 'Classes', 'url' => ['/classes/index']],

            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login'],'class' => 'navbar-right'] //
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Anton <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
