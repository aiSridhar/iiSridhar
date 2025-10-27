<?php

use kartik\grid\EditableColumn;
use kartik\popover\PopoverX;
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\editable\Editable;
use app\common\helpers\ahtml;

//require_once "/home/anton/proj/nanobot.admin/www/yii2/common/helpers/ahtml.php";

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClassesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Classes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Classe', ['create'], ['class' => 'btn btn-success']) ?>


    </p>


    scripts->classes->messages->filters




    <?php
    //table-hover table-condensed
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=> function ($model, $index, $widget, $grid){

            $class="";
            if($model["enabled"]==0) $class="danger";  else {
                if($model["step"]==0) $class= "warning";
            }
            return ['class'=>$class];
        },
        'columns' => [
                ['class' => 'yii\grid\CheckboxColumn',
                    'name' => 'calsses[ids]',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id];
                }],
            ['attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['style' => 'width:70px'],
            ],


                [
                    'attribute' => 'ID',
                    'format' => 'html',
                    'label' => 'ID',
                    'value' => function ($data) { return ahtml::enabled($data).ahtml::script_id($data).ahtml::step($data);},

                    'headerOptions' => ['style' => 'width:100px'],

                ],


                [
                      'attribute' => 'Icon',
                      'format' => 'html',
                      'label' => 'c',
                      'value' => function ($data) { return ahtml::messages_in_counts($data).ahtml::messages_out_counts($data);},
                    'headerOptions' => ['style' => 'width:300px'],

                ],


                [
                    'attribute' => 'action',
                    'format' => 'html',
                    'label' => 'action',
                    'value' => function ($data) { return ahtml::actions($data); },
                    'headerOptions' => ['style' => 'width:100px'],

                ],

                [
                    'attribute' => 'if',
                    'format' => 'html',
                    'label' => 'if',
                    'value' => function ($data) { return ahtml::ifs($data); },
                    'headerOptions' => ['style' => 'width:100px'],

                ],


            [
                'attribute'       => 'name',
                'class'           => EditableColumn::class,
                'label'           => 'name',
                'contentOptions'  => ['style' => 'max-width: 100px;word-wrap: break-word;overflow-wrap: break-word;'],
                'editableOptions' => [
                    'size'        => PopoverX::SIZE_LARGE,
                    'placement'   => PopoverX::ALIGN_LEFT,
                    'formOptions' => ['action' => ['edit-name']],
                    'inputType'   => Editable::INPUT_TEXT,
                ],
                'value'           => 'name'
            ],
//            [
//                'attribute'      => 'name',
//                'label'          => 'name',
//                'headerOptions'  => ['style' => 'width:100px'],
//                'contentOptions' => ['style' => 'max-width: 100px;word-wrap: break-word;overflow-wrap: break-word;'],
//            ],


            //'name',
            //'script_id',
            //'step',
            //'priority',

            /*

            [
                'attribute'       => 'keywords',
                'class'           => EditableColumn::class,
                'label'           => 'keywords',
                'contentOptions'  => ['style' => 'max-width: 100px;word-wrap: break-word;overflow-wrap: break-word;'],
                'editableOptions' => [
                    'size'        => PopoverX::SIZE_LARGE,
                    'placement'   => PopoverX::ALIGN_LEFT,
                    'formOptions' => ['action' => ['edit-keywords']],
                    'inputType'   => Editable::INPUT_TEXTAREA,
                ],
                'value'           => 'keywords'
            ],
            [
                'attribute'       => 'value',
                'class'           => EditableColumn::class,
                'label'           => 'response',
                'contentOptions'  => ['style' => 'max-width: 100px;word-wrap: break-word;overflow-wrap: break-word;'],
                'editableOptions' => [
                    'size'        => PopoverX::SIZE_LARGE,
                    'placement'   => PopoverX::ALIGN_LEFT,
                    'formOptions' => ['action' => ['edit-value']],
                    'inputType'   => Editable::INPUT_TEXTAREA,
                ],
                'value'           => 'value'
            ],
            [
                'attribute'       => 'example',
                'class'           => EditableColumn::class,
                'label'           => 'example',
                'contentOptions'  => ['style' => 'max-width: 100px;word-wrap: break-word;overflow-wrap: break-word;'],
                'editableOptions' => [
                    'size'        => PopoverX::SIZE_LARGE,
                    'placement'   => PopoverX::ALIGN_LEFT,
                    'formOptions' => ['action' => ['edit-example']],
                    'inputType'   => Editable::INPUT_TEXTAREA,
                ],
                'value'           => 'example'
            ],

            */

            ['attribute' => 'keywords',
                'label' => 'keywords',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<textarea cols='30' rows='4' readonly class='form-control' style='padding: 1px; font-size: 10px;'>".htmlentities($data["keywords"])."</textarea>";
                },
                'headerOptions' => ['style' => 'width:300px'],
            ],

            ['attribute' => 'value',
                'label' => 'response',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<textarea cols='30' rows='4' readonly class='form-control' style='padding: 1px; font-size: 10px;'>".htmlentities($data["value"])."</textarea>";
                },
                'headerOptions' => ['style' => 'width:300px'],
            ],

            ['attribute' => 'example',
                'label' => 'example',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<textarea cols='30' rows='4' readonly class='form-control' style='padding: 1px; font-size: 10px;'>".htmlentities($data["example"])."</textarea>";
                },
                'headerOptions' => ['style' => 'width:300px'],
            ],


/*
            [
                'class'=>'kartik\grid\EditableColumn',
                'editableOptions'=>[
                    'asPopover' => false,
                    'inputType'=>\kartik\editable\Editable::INPUT_TEXTAREA,
                ],

                'attribute'=>'keywords',
                'label'=>'keywords',

            ],
*/

            //'keywords:ntext',
            //'value:ntext',
            //'example:ntext',

            //'post',
            //'keywords_norm:ntext',
            //'action_exec_next:boolean',
            //'action_resend',
            //'action_vktalker_bl',
            //'action_set_list_id',
            //'action_set_script_id',
            //'action_set_step',
            //'action_finish',
            //'action_work_mode',
            //'action_pause_sec_block',
            //'action_pause_sec_nonblock',
            //'if_platform',
            //'if_male:boolean',
            //'if_age_min',
            //'if_age_max',
            //'if_list_id',
            //'if_engine',
            //'if_city_id',
            //'action_response_clear:boolean',
            //'count_today',
            //'count_total',
            //'count_yesterday',
            //'date_last_count',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

<?=Html::beginForm(['controller/bulk'],'post');?>

    <?=Html::dropDownList('action','',[''=>'Выбранное: ','del'=>'Удалить','disable'=>'Выключить','enable'=>'Включить','copy'=>'Скопировать','copyto'=>'Скопировать в','move'=>'Переместить в'],['class'=>'dropdown',])?>
    <?=Html::dropDownList('project_id','',[''=>'выберите проект'],['class'=>'dropdown',])?>
    <?=Html::submitButton('Выполнить', ['class' => 'btn btn-info',]);?>


<?= Html::endForm(); ?>



</div>
