<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

require_once "/home/anton/proj/nanobot.admin/www/yii2/common/helpers/ahtml.php";

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessagesOutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Messages Outs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-out-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>



    <?php
    if(
            ($searchModelNoClass["ca_id"]>0)||
            ($searchModelNoClass["account_id"]>0)||
            ($searchModelNoClass["script_id"]>0)
    )
    if($dataProviderNoClass->count<=0)
        echo("<h3>noclass count=0</h3>");
        else {
        echo("<h2>noclass</h2>");

        echo GridView::widget([
            'dataProvider' => $dataProviderNoClass,
            'filterModel' => $searchModelNoClass,
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn',
                    'name' => 'calsses[ids]',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return ['value' => $model->id];
                    }],
                ['attribute' => 'id',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width:70px'],
                ],

                ['attribute' => 'date',
                    'label' => 'date',
                    'content' => function ($data) {
                        return "<span class='small'>" . date("H:i:s", strtotime($data["date_send"])) . "</span><br><span class='text-muted small'>" . date("d.m.y", strtotime($data["date_send"])) . "</span>";
                    },
                    'headerOptions' => ['style' => 'width:70px'],
                ],



                [
                    'attribute' => 'Icon',
                    'format' => 'html',
                    'label' => 'e',
                    'value' => function ($data){ return ahtml::engine($data);},
                    'options' => ['class' => 'text-nowrap'],
                    'headerOptions' => ['style' => 'width:20px'],

                ],


                [
                    'attribute' => 'acc',
                    'format' => 'html',
                    'label' => 'acc/ca',
                    'value' => function ($data) { return ahtml::account_out($data).ahtml::ca_out($data); },

                    'options' => ['class'=>'text-nowrap']

                ],


                [
                    'attribute' => 'Icon',
                    'format' => 'html',
                    'label' => 'path',
                    'value' => function ($data) {


                        $res = "
                        <a href ='/admin/scripts/update?id=" . $data["script_id"] . "'><img src='/admin/imgs/f/script-block.png'>=" . $data["script_id"] . "</a><br>
                        <a href ='/admin/scripts/update?id=" . $data["script_id"] . "'><img src='/admin/imgs/f/arrow-curve-000-left.png'></a>=" . $data["step"] . "<br>
                    ";

                        if ($data["class_id"] > 0)
                            $res .= "
                                     <a href ='/admin/classes/index?ClassesSearch%5Bclass_id%5D=" . $data["class_id"] . "'><img src='/admin/imgs/f/scripts-text.png'></a>=" . $data["class_id"] . "<br>";

                        return $res;

                    },
                    'options' => ['class' => 'text-nowrap']

                ],


                ['attribute' => 'in',
                    'label' => 'in',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return "<textarea cols='30' rows='4' readonly class='form-control' style='padding: 1px; font-size: 10px;'>" . htmlentities($data["text"]) . "</textarea>";
                    },
                    'headerOptions' => ['style' => 'width:300px'],
                ],

                [
                    'attribute' => 'out',
                    'format' => 'html',
                    'label' => 'out',
                    'value' => function ($data) {
                        if ($data["class_id"] > 0)
                            $res = "
                            <a href ='/admin/messages-out/index?MessagesInGroupSearch%5Bclass_id%5D=" . $data["class_id"] . "'><img src='/admin/imgs/f/navigation-090.png'></a><br>
                            ";
                        else
                            $res = "
                            <a href ='/admin/messages-in-group/index?MessagesInGroupSearch%5Bscript_id%5D=" . $data["script_id"] . "&MessagesInGroupSearch%5Bstep%5D=" . $data["step"] . "'><img src='/admin/imgs/f/cross-white.png'></a><br>
                            ";

                        return $res;
                    },
                    'headerOptions' => ['style' => 'width:300px'],
                ],

                //'engine',
                //'script_id',
                //'step',
                //'account_id',
                //'ca_id',
                //'text:ntext',
                //'text_norm:ntext',
                //'date_send',
                //'date_get',
                //'date_norm',
                //'date_class',
                //'class_engine',
                //'class_id',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    }
    ?>

    <h2>out</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            ['attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['style' => 'width:70px'],
            ],


            ['attribute' => 'date',
                'label' => 'date',
                'content' => function ($data) {
                    return "<span class='small'>".date("H:i:s",strtotime($data["date_send"]))."</span><br><span class='text-muted small'>".date("d.m.y",strtotime($data["date_send"]))."</span>";
                },
                'headerOptions' => ['style' => 'width:70px'],
            ],

            [
                'attribute' => 'Icon',
                'format' => 'html',
                'label' => 'e',
                'value' => function ($data) {
                    $res="";
                    if($data["engine"]=="vk")
                        $res.="<img src='/admin/imgs/nav/vk.ico' width='16px' height='16px'><br>";
                    elseif($data["engine"]=="ok")
                        $res.="<img src='/admin/imgs/nav/ok.ico' width='16px' height='16px'><br>";
                    elseif($data["engine"]=="inst")
                        $res.="<img src='/admin/imgs/nav/inst.ico' width='16px' height='16px'><br>";

                    return $res;

                },
                'options' => ['class'=>'text-nowrap'],
                'headerOptions' => ['style' => 'width:20px'],
            ],

            [
                'attribute' => 'acc',
                'format' => 'html',
                'label' => 'acc/ca',
                'value' => function ($data) { return ahtml::account_out($data).ahtml::ca_out($data); },

                'options' => ['class'=>'text-nowrap']

            ],




            [
                'attribute' => 'Icon',
                'format' => 'html',
                'label' => 'path',
                'value' => function ($data) {


                    $res="
                        <a href ='/admin/scripts/update?id=".$data["script_id"]."'><img src='/admin/imgs/f/script-block.png'>=".$data["script_id"]."</a><br>
                        <a href ='/admin/scripts/update?id=".$data["script_id"]."'><img src='/admin/imgs/f/arrow-curve-000-left.png'></a>=".$data["step"]."<br>
                    ";

                    if($data["class_id"]>0)
                        $res.="
                                     <a href ='/admin/classes/index?ClassesSearch%5Bclass_id%5D=".$data["class_id"]."'><img src='/admin/imgs/f/scripts-text.png'></a>=".$data["class_id"]."<br>";

                    return $res;

                },
                'options' => ['class'=>'text-nowrap']

            ],

            [
                'attribute' => 'action',
                'format' => 'html',
                'label' => 'action',
                'value' => function ($data) {
                    $message_in_group=$data->get_messages_in_group();
                    //print_r($message_in_group);
                    return ahtml::actions($message_in_group->get_class());
                     },
                'headerOptions' => ['style' => 'width:100px'],

            ],

            ['attribute' => 'in',
                'label' => 'in',
                'format' => 'raw',
                'value' => function ($data) {
                    $message_in_group=$data->get_messages_in_group();
                    return "<textarea cols='30' rows='4' readonly class='form-control' style='padding: 1px; font-size: 10px;'>".htmlentities($message_in_group["text"])."</textarea>";
                },
                'headerOptions' => ['style' => 'width:300px'],
            ],

            ['attribute' => 'text',
                'label' => 'out',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<textarea cols='30' rows='4' readonly class='form-control' style='padding: 1px; font-size: 10px;'>".htmlentities($data["text_send"])."</textarea>";
                },
                'headerOptions' => ['style' => 'width:300px'],
            ],

            /*


[
                'attribute' => 'Icon',
                'format' => 'html',
                'label' => 'c',
                'value' => function ($data) { return "
<a href ='/admin/scripts/update?id=".$data["script_id"]."'><img src='/admin/imgs/f/script-block.png'></a>
<a href ='/admin/classes/index?ClassesSearch%5Bscript_id%5D=".$data["class_id"]."'><img src='/admin/imgs/f/scripts-text.png'></a>
<a href ='/admin/messages-in-group/index?MessagesInGroupSearch%5Bscript_id%5D=".$data["id"]."'><img src='/admin/imgs/f/navigation-270-white.png'></a>



"; }

            ],





//картинку - 'group_id',

            'id',
            //'engine',
//картинку            'script_id',
            'step',
//картинку            'class_id',
            'account_id',
            'ca_id',
            //'external_id',
            'text_send:ntext',
            'sended',
            //'date_ready',
            //'date_send',
            */

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>



    <?php Pjax::end(); ?>
</div>
