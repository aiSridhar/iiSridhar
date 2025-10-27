<?php

use kartik\popover\PopoverX;
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use app\common\helpers\ahtml;

use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScriptsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scripts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scripts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Script', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=> function ($model, $index, $widget, $grid){
            $class="";
            if($model["enabled"]==0) $class="danger";
             return ['class'=>$class];
        },
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

            [
                'attribute' => 'actions',
                'format' => 'html',
                'label' => 's',
                'value' => function ($data) { return ahtml::menu($data);},
                'headerOptions' => ['style' => 'width:70px'],
            ],

            ['attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['style' => 'width:70px'],
            ],


            [
                'attribute' => 'classes',
                'format' => 'html',
                'label' => 'classes',
                'value' => function ($data) { return ahtml::classes($data); },
                'headerOptions' => ['style' => 'width:70px'],
            ],

            [
                'attribute' => 'counts',
                'format' => 'html',
                'label' => 'counts',
                'value' => function ($data) { return ahtml::dialogs_counts($data).ahtml::messages_in_counts($data).ahtml::messages_out_counts($data).ahtml::messages_in_noclass_counts($data);},
                'headerOptions' => ['style' => 'width:300px'],

            ],

            [
                'attribute' => 'name',
                'class'     => EditableColumn::class,
                'editableOptions' => [
                    'size'        => PopoverX::SIZE_LARGE,
                    'placement'   => PopoverX::ALIGN_LEFT,
                    'formOptions' => ['action' => ['edit-name']],
                    'inputType'   => Editable::INPUT_TEXT,
                ],
                'value' => 'name'
            ],

//            'name',
            //'enabled:boolean',
            //'use_norm:boolean',
            //'use_orfo:boolean',
            //'use_translit:boolean',
            //'ai_keyword:boolean',
            //'ai_bayes:boolean',
            //'ai_neuro:boolean',
            //'ai_expert:boolean',
            //'ai_operator:boolean',
            //'count_today',
            //'count_total',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
