<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MessagesOut */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="messages-out-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'engine')->dropDownList([ 'vk' => 'Vk', 'ok' => 'Ok', 'fb' => 'Fb', 'inst' => 'Inst', 'li' => 'Li', 'tel' => 'Tel', 'vktalker' => 'Vktalker', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'script_id')->textInput() ?>

    <?= $form->field($model, 'step')->textInput() ?>

    <?= $form->field($model, 'class_id')->textInput() ?>

    <?= $form->field($model, 'account_id')->textInput() ?>

    <?= $form->field($model, 'ca_id')->textInput() ?>

    <?= $form->field($model, 'external_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text_send')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'group_id')->textInput() ?>

    <?= $form->field($model, 'sended')->textInput() ?>

    <?= $form->field($model, 'date_ready')->textInput() ?>

    <?= $form->field($model, 'date_send')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
