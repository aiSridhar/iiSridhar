<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MessagesOutSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="messages-out-search">

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

            <div class="col-sm-3"><?= $form->field($model, 'script_id') ?></div>

            <div class="col-sm-3"><?= $form->field($model, 'step') ?></div>

            <div class="col-sm-3"><?= $form->field($model, 'class_id') ?></div>
        </div>
        <div class="row">
            <div class="col-sm-3"><?= $form->field($model, 'account_id') ?></div>

            <div class="col-sm-3"><?= $form->field($model, 'ca_id') ?></div>

            <div class="col-sm-3"><?= $form->field($model, 'engine') ?></div>

            <div class="col-sm-3">
                <div class="form-group">

                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
                </div>
            </div>

        </div>
    </div>






    <?php ActiveForm::end(); ?>

</div>
