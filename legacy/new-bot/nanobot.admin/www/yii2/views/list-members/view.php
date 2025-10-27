<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ListMembers */

$this->title = $model->list_id;
$this->params['breadcrumbs'][] = ['label' => 'List Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-members-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'list_id' => $model->list_id, 'engine' => $model->engine, 'ca_id' => $model->ca_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'list_id' => $model->list_id, 'engine' => $model->engine, 'ca_id' => $model->ca_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'list_id',
            'engine',
            'ca_id',
            'date_add',
        ],
    ]) ?>

</div>
