<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ListMembers */

$this->title = 'Create List Members';
$this->params['breadcrumbs'][] = ['label' => 'List Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
