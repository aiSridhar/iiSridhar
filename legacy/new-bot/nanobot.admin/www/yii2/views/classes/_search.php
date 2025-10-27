<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClassesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="classes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="container">
        <div class="row">

            <div class="col-sm-3"><?= $form->field($model, 'id') ?></div>

            <div class="col-sm-3"><?= $form->field($model, 'name') ?></div>

            <div class="col-sm-2"><?= $form->field($model, 'script_id') ?></div>

            <div class="col-sm-2"><?= $form->field($model, 'step') ?></div>
            <div class="col-sm-2">
                <div class="form-group">

                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
                </div>
            </div>

        </div>
    </div>

    <?php //$form->field($model, 'post') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'example') ?>

    <?php // echo $form->field($model, 'keywords_norm') ?>

    <?php // echo $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'action_exec_next')->checkbox() ?>

    <?php // echo $form->field($model, 'action_resend') ?>

    <?php // echo $form->field($model, 'action_vktalker_bl') ?>

    <?php // echo $form->field($model, 'action_set_list_id') ?>

    <?php // echo $form->field($model, 'action_set_script_id') ?>

    <?php // echo $form->field($model, 'action_set_step') ?>

    <?php // echo $form->field($model, 'action_finish') ?>

    <?php // echo $form->field($model, 'action_work_mode') ?>

    <?php // echo $form->field($model, 'action_pause_sec_block') ?>

    <?php // echo $form->field($model, 'action_pause_sec_nonblock') ?>

    <?php // echo $form->field($model, 'if_platform') ?>

    <?php // echo $form->field($model, 'if_male')->checkbox() ?>

    <?php // echo $form->field($model, 'if_age_min') ?>

    <?php // echo $form->field($model, 'if_age_max') ?>

    <?php // echo $form->field($model, 'if_list_id') ?>

    <?php // echo $form->field($model, 'if_engine') ?>

    <?php // echo $form->field($model, 'if_city_id') ?>

    <?php // echo $form->field($model, 'action_response_clear')->checkbox() ?>

    <?php // echo $form->field($model, 'count_today') ?>

    <?php // echo $form->field($model, 'count_total') ?>

    <?php // echo $form->field($model, 'count_yesterday') ?>

    <?php // echo $form->field($model, 'date_last_count') ?>



    <?php ActiveForm::end(); ?>

</div>
