<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MessagesInGroup */

$this->title = 'Create Messages In Group';
$this->params['breadcrumbs'][] = ['label' => 'Messages In Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-in-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
