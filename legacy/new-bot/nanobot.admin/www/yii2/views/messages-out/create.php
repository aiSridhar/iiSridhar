<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MessagesOut */

$this->title = 'Create Messages Out';
$this->params['breadcrumbs'][] = ['label' => 'Messages Outs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-out-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
