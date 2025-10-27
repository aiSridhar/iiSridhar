<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MessagesOut */

$this->title = 'Update Messages Out: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Messages Outs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="messages-out-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
