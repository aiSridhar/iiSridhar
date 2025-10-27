<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Scripts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scripts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'enabled')->checkbox() ?>






    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
        <li role="presentation"><a href="#norm" aria-controls="norm" role="tab" data-toggle="tab">Norm</a></li>
        <li role="presentation"><a href="#ai" aria-controls="ai" role="tab" data-toggle="tab">AI</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general">

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
