<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ListMembers */

$this->title = 'Update List Members: ' . $model->list_id;
$this->params['breadcrumbs'][] = ['label' => 'List Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->list_id, 'url' => ['view', 'list_id' => $model->list_id, 'engine' => $model->engine, 'ca_id' => $model->ca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="list-members-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
