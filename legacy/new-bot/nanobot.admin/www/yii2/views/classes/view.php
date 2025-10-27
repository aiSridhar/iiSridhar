<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Classes */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classes-view">

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
            'script_id',
            'step',
            'keywords:ntext',
            'value:ntext',
            'example:ntext',
            'keywords_norm:ntext',
            'priority',
            'action_exec_next:boolean',
            'action_resend',
            'action_vktalker_bl',
            'action_set_list_id',
            'action_set_script_id',
            'action_set_step',
            'action_finish',
            'action_work_mode',
            'action_pause_sec_block',
            'action_pause_sec_nonblock',
            'if_platform',
            'if_male:boolean',
            'if_age_min',
            'if_age_max',
            'if_list_id',
            'if_engine',
            'if_city_id',
            'action_response_clear:boolean',
            'date_last_count',
        ],
    ]) ?>

</div>
