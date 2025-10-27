<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Scripts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Scripts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scripts-view">

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
            'name',
            'enabled:boolean',
            'use_stem:boolean',
            'use_orfo:boolean',
            'use_translit:boolean',
            'ai_keyword:boolean',
            'ai_bayes:boolean',
            'ai_neuro:boolean',
            'ai_expert:boolean',
            'ai_operator:boolean',
        ],
    ]) ?>

</div>
