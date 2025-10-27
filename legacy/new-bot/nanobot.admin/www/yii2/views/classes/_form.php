<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Classes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="classes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'enabled')->checkbox() ?>


    <?= $form->field($model, 'script_id')->textInput() ?>

    <?= $form->field($model, 'step')->textInput() ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
        <li role="presentation"><a href="#if" aria-controls="if" role="tab" data-toggle="tab">if</a></li>
        <li role="presentation"><a href="#action" aria-controls="action" role="tab" data-toggle="tab">action</a></li>
        <li role="presentation"><a href="#norm" aria-controls="norm" role="tab" data-toggle="tab">Norm</a></li>
        <li role="presentation"><a href="#ai" aria-controls="ai" role="tab" data-toggle="tab">AI</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general">



            <?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'example')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'keywords_norm')->textarea(['rows' => 6]) ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="if">
            <?= $form->field($model, 'if_platform')->dropDownList([ 'desk' => 'Desk', 'mob' => 'Mob', 'android' => 'Android', 'ios' => 'Ios', ], ['prompt' => '']) ?>

            <?= $form->field($model, 'if_male')->dropDownList([ '1' => 'male','0' => 'female', ], ['prompt' => '']) ?>

            <?= $form->field($model, 'if_age_min')->textInput() ?>

            <?= $form->field($model, 'if_age_max')->textInput() ?>

            <?= $form->field($model, 'if_list_id')->textInput() ?>

            <?= $form->field($model, 'if_not_list_id')->textInput() ?>

            <?= $form->field($model, 'if_engine')->dropDownList([ 'ok' => 'ok', 'vk' => 'vk', ], ['prompt' => '']) ?>

            <?= $form->field($model, 'if_city_id')->textInput() ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="action">
            <?= $form->field($model, 'action_exec_next')->dropDownList([ '1' => 'True', ], ['prompt' => '']) ?>

            <?= $form->field($model, 'action_resend')->textInput() ?>

            <?= $form->field($model, 'action_vktalker_bl')->textInput() ?>

            <?= $form->field($model, 'action_set_list_id')->textInput() ?>

            <?= $form->field($model, 'action_set_script_id')->textInput() ?>

            <?= $form->field($model, 'action_set_step')->textInput() ?>

            <?= $form->field($model, 'action_finish')->textInput() ?>

            <?= $form->field($model, 'action_work_mode')->dropDownList([ 'fast' => 'Fast', 'slow' => 'Slow', ], ['prompt' => '']) ?>

            <?= $form->field($model, 'action_pause_sec_block')->textInput() ?>

            <?= $form->field($model, 'action_pause_sec_nonblock')->textInput() ?>

            <?= $form->field($model, 'action_response_clear')->dropDownList([ '1' => 'True', ], ['prompt' => ''])  ?>
        </div>



        <div role="tabpanel" class="tab-pane" id="norm">
            <?= $form->field($model, 'use_stem')->checkbox() ?>

            <?= $form->field($model, 'use_orfo')->checkbox() ?>

            <?= $form->field($model, 'use_translit')->checkbox() ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="ai">
            <?= $form->field($model, 'ai_keyword')->checkbox() ?>

            <?= $form->field($model, 'ai_bayes')->checkbox() ?>

            <?= $form->field($model, 'ai_neuro')->checkbox() ?>

            <?= $form->field($model, 'ai_expert')->checkbox() ?>

            <?= $form->field($model, 'ai_operator')->checkbox() ?>

        </div>

    </div>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
