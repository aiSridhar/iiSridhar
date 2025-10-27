<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
require_once "/home/anton/proj/nanobot/www/yii2/common/helpers/ahtml.php";
/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Accounts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',
                'name' => 'calsses[ids]',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id];
                }],



            [
                'attribute' => 'Icon',
                'format' => 'html',
                'label' => 'e',
                'value' => function ($data){ return ahtml::engine($data);},
                'options' => ['class' => 'text-nowrap'],
                'headerOptions' => ['style' => 'width:20px'],

            ],

            ['attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['style' => 'width:70px'],
            ],

            'status',

            [
                'attribute' => 'counts',
                'format' => 'html',
                'label' => 'counts',
                'value' => function ($data) { return ahtml::dialogs_counts($data).ahtml::messages_in_counts($data).ahtml::messages_out_counts($data);},
                'headerOptions' => ['style' => 'width:300px'],

            ],

            [
                'attribute' => 'ready',
                //'format' => 'html',
                'label' => 'ready',
                'value' => function ($data) { return $data->get_count_ca_ready(); //ahtml::ca_ready_counts($data);
                },
                'headerOptions' => ['style' => 'width:300px'],

            ],

            [
                'attribute' => 'date_message_last',
                'format' => 'html',
                'label' => 'date_message_last',
                'value' => function ($data) { return $data->get_message_last();},
                'headerOptions' => ['style' => 'width:300px'],

            ],

            'date_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
