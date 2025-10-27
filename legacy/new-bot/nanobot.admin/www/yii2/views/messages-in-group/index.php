<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MessagesInGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

require_once "/home/anton/proj/nanobot.admin/www/yii2/common/helpers/ahtml.php";

$this->title = 'Messages In Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-in-group-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Messages In Group', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                'attribute' => 't',
                'format' => 'html',
                'label' => 'ID',
                'value' => function ($data) {
                    if($data["class_id"]>0)
                        $res="
                            <a href ='/admin/messages-out/index?MessagesInGroupSearch%5Bclass_id%5D=".$data["class_id"]."'><img src='/admin/imgs/f/navigation-090.png'></a><br>
                            ";
                    else
                        $res="
                            <a href ='/admin/messages-in-group/index?MessagesInGroupSearch%5Bscript_id%5D=".$data["script_id"]."&MessagesInGroupSearch%5Bstep%5D=".$data["step"]."'><img src='/admin/imgs/f/cross-white.png'></a><br>
                            ";

                    return $res;
                },
                'options' => ['class'=>'text-nowrap']
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

            ['attribute' => 'text',
                'label' => 'text',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<textarea cols='30' rows='4' readonly class='form-control' style='padding: 1px; font-size: 10px;'>".htmlentities($data["text"])."</textarea>";
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
    ]); ?>
    <?php Pjax::end(); ?>
</div>
