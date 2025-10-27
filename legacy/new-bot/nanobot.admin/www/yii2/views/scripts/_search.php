<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScriptsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scripts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'enabled')->checkbox() ?>

    <?php // echo $form->field($model, 'use_translit')->checkbox() ?>

    <?php // echo $form->field($model, 'ai_keyword')->checkbox() ?>

    <?php // echo $form->field($model, 'ai_bayes')->checkbox() ?>

    <?php // echo $form->field($model, 'ai_neuro')->checkbox() ?>

    <?php // echo $form->field($model, 'ai_expert')->checkbox() ?>

    <?php // echo $form->field($model, 'ai_operator')->checkbox() ?>

    <?php // echo $form->field($model, 'count_today') ?>

    <?php // echo $form->field($model, 'count_total') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
