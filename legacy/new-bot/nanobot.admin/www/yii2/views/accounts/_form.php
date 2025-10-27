<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Accounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accounts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'engine')->dropDownList([ 'vk' => 'Vk', 'ok' => 'Ok', 'fb' => 'Fb', 'inst' => 'Inst', 'li' => 'Li', 'tel' => 'Tel', 'vktalker' => 'Vktalker', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'ok' => 'Ok', 'block' => 'Block', 'ban' => 'Ban', 'security' => 'Security', 'security2' => 'Security2', 'zero' => 'Zero', 'proxy' => 'Proxy', 'password' => 'Password', 'hz' => 'Hz', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'date_message_first')->textInput() ?>

    <?= $form->field($model, 'date_message_last')->textInput() ?>

    <?= $form->field($model, 'date_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
