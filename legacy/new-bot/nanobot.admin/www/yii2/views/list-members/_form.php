<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ListMembers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="list-members-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'list_id')->textInput() ?>

    <?= $form->field($model, 'engine')->dropDownList([ 'ok' => 'Ok', 'fb' => 'Fb', 'vk' => 'Vk', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'ca_id')->textInput() ?>

    <?= $form->field($model, 'date_add')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
