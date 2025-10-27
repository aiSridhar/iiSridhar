<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MessagesInGroup */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Messages In Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-in-group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'engine',
            'script_id',
            'step',
            'account_id',
            'ca_id',
            'text:ntext',
            'text_norm:ntext',
            'date_send',
            'date_get',
            'date_norm',
            'date_class',
            'class_engine',
            'class_id',
        ],
    ]) ?>

</div>
